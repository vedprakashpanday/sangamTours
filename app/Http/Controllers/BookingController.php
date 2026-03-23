<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\TourPackage;
use Illuminate\Container\Attributes\Log as AttributesLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class BookingController extends Controller
{
public function index()
{
    $bookings = Booking::with(['customer', 'vehicle', 'route', 'package'])->orderBy('id', 'desc')->get();
    $customers = Customer::where('status', 1)->get();
    $routes = Route::with(['fromCity', 'toCity'])->where('status', 1)->get();
    $packages = TourPackage::where('status', 1)->get();
    $vehicles = \App\Models\Vehicle::with('vendor')->where('status', 1)->get();
    
    // 🔥 Ye line add karein: Locations fetch karne ke liye
    $locations = \App\Models\Location::all(); 
    
    $offers = \App\Models\Offer::where('status', 1)
                ->where('valid_until', '>=', date('Y-m-d'))
                ->get();

    // compact mein 'locations' add kar diya hai
    return view('admin.bookings.index', compact('bookings', 'customers', 'routes', 'packages', 'vehicles', 'offers', 'locations'));
}
private function getCoords($cityName) {
    $apiKey = 'kZlrr6Y0pPOlTYLmuIL13pIKNl2lkOb3mI1CO2pQGaOWcd46tEj6KzxnmyBHrA7X=';

    // 🔥 FIX 1: Query ko ekdum specific kiya Bihar ke liye
    $searchQuery = $cityName . ", Bihar, India";

    $response = Http::get("https://api.openrouteservice.org/geocode/search", [
        'api_key' => $apiKey,
        'text'    => $searchQuery,
        'boundary.country' => 'IND', // Sirf India mein dhoondho
        'size'    => 1
    ]);

    if ($response->successful() && isset($response['features'][0])) {
        $coords = $response['features'][0]['geometry']['coordinates'];
        Log::info("Coords found for $cityName: " . json_encode($coords)); // Debugging ke liye
        return $coords;
    }

    Log::error("Geocoding failed for: " . $searchQuery);
    return null;
}


private function getDistanceBetweenCities($fromLoc, $toLoc) {
    // 1. Database Check (Hamesha pehle DB dekho, API bachao)
    $route = Route::where('from_city_id', $fromLoc->id)
                  ->where('to_city_id', $toLoc->id)
                  ->first();

    if ($route && $route->distance_km > 0.1) {
        return (float)$route->distance_km;
    }

    // 2. API Call Logic
    try {
        $apiKey = 'kZlrr6Y0pPOlTYLmuIL13pIKNl2lkOb3mI1CO2pQGaOWcd46tEj6KzxnmyBHrA7X'; // Apni key yahan daalein
        
        // Bihar ke locations ke liye context zaroori hai
        $origin = urlencode($fromLoc->city_location . ", Bihar, India");
        $destination = urlencode($toLoc->city_location . ", Bihar, India");

        $url = "https://api.distancematrix.ai/maps/api/distancematrix/json?origins={$origin}&destinations={$destination}&key={$apiKey}";

        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['rows'][0]['elements'][0]['distance'])) {
                $distMeters = $data['rows'][0]['elements'][0]['distance']['value'];
                $distKm = round($distMeters / 1000, 2);

                // 3. Database mein Save karein taaki hamesha ke liye fixed ho jaye
                Route::updateOrCreate(
                    ['from_city_id' => $fromLoc->id, 'to_city_id' => $toLoc->id],
                    ['distance_km' => $distKm]
                );

                return $distKm;
            }
        }
    } catch (\Exception $e) {
        Log::error("API Error: " . $e->getMessage());
    }

    return 0;
}


public function checkAvailability(Request $request) {
    $fromLocId = $request->from; 
    $toLocId = $request->to;     
    $type = $request->type;      

    $fromLoc = \App\Models\Location::find($fromLocId);
    $toLoc = \App\Models\Location::find($toLocId);

    if(!$fromLoc || !$toLoc) return response()->json(['schedules' => []]);

    // --- STEP 1: DISTANCE LOGIC (With Manual Fallback) ---
    $dist = $this->getDistanceBetweenCities($fromLoc, $toLoc);

    // 🔥 JUGAD: Agar API 0 de rahi hai, toh Bihar ke common routes yahan manually handle kar lo
    if ($dist <= 0) {
        $manualRoutes = [
            'Hajipur-Sonpur' => 6.0,
            'Sonpur-Hajipur' => 6.0,
            'Patna-Hajipur' => 21.0,
            'Hajipur-Patna' => 21.0,
            'Hajipur-Darbhanga' => 145.0,
            'Sonpur-Darbhanga' => 150.0,
        ];
        
        $routeKey = $fromLoc->city_location . '-' . $toLoc->city_location;
        $dist = $manualRoutes[$routeKey] ?? 0;
    }

    // --- STEP 2: FETCH SCHEDULES ---
    $schedules = Schedule::with(['vehicle.vendor', 'stoppages'])
        ->whereHas('vehicle', function($q) use ($type) {
            $q->where('type', $type);
        })
        ->whereHas('stoppages', function($q) use ($fromLocId) {
            $q->where('location_id', $fromLocId);
        })
        ->whereHas('stoppages', function($q) use ($toLocId) {
            $q->where('location_id', $toLocId);
        })
        ->get();

    $availableOptions = [];

    // --- STEP 3: ORDER & FARE CALCULATION ---
    foreach ($schedules as $s) {
        $stoppages = $s->stoppages;

        $boardingData = $stoppages->where('location_id', $fromLocId)->first();
        $destinationData = $stoppages->where('location_id', $toLocId)->first();

        // Direction Check: Boarding order destination se kam hona chahiye
        if ($boardingData && $destinationData && $boardingData->stop_order < $destinationData->stop_order) {
            
            $perKm = (float)($s->vehicle->charges_per_km ?? 0);
            
            // 🔥 TOTAL FARE CALCULATION
            // Agar distance abhi bhi 0 hai, toh fare bhi 0 dikhega (Manual update warning ke liye)
            $farePerPerson = ($dist > 0) ? ($dist * $perKm) : 0;

            $availableOptions[] = [
                'id'           => $s->id,
                'vehicle_name' => $s->vehicle->vehicle_number,
                'vendor'       => $s->vehicle->vendor->name,
                'distance'     => round($dist, 2),
                'per_km'       => $perKm,
                'fare'         => round($farePerPerson, 2),
                'from_name'    => $fromLoc->city_location, 
                'to_name'      => $toLoc->city_location,   
                'departure'    => date('h:i A', strtotime($boardingData->departure_time)),
                'arrival'      => date('h:i A', strtotime($destinationData->arrival_time)),
            ];
        }
    }

    return response()->json(['schedules' => $availableOptions]);
}

public function store(Request $request)
{
    // 1. Auto-generate Booking Number (e.g., ST-2026-0001)
    $year = date('Y');
    $lastBooking = \App\Models\Booking::latest()->first();
    $nextId = $lastBooking ? ($lastBooking->id + 1) : 1;
    $bookingNo = "ST-" . $year . "-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);

    // 2. Data save logic
    \App\Models\Booking::create([
        'booking_no'    => $bookingNo,
        'customer_id'   => $request->customer_id,
        'service_type'  => $request->service_type,
        'vehicle_id'    => $request->vehicle_id,
        'route_id'      => $request->route_id,
        'package_id'    => $request->package_id,
        'travel_date'   => $request->travel_date,
        'pax_count'     => $request->pax_count,
        'total_amount'  => $request->total_amount,
        'paid_amount'   => $request->paid_amount,
        'due_amount'    => $request->due_amount,
        'payment_status'=> ($request->due_amount <= 0) ? 'Paid' : (($request->paid_amount > 0) ? 'Partial' : 'Pending'),
        'booking_status'=> 'Upcoming',
    ]);

    return response()->json([
        'status'  => 'success', 
        'message' => 'Booking Confirmed! Booking No: ' . $bookingNo
    ]);
}


}
