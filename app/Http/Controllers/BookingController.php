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
    $toLocId   = $request->to;     
    $type      = $request->type;      

    // Distance & Locations Fetching
    $fromLoc = \App\Models\Location::find($fromLocId);
    $toLoc   = \App\Models\Location::find($toLocId);
    if(!$fromLoc || !$toLoc) return response()->json(['schedules' => []]);

    $dist = $this->getDistanceBetweenCities($fromLoc, $toLoc); // Aapka existing distance function

    // --- 🔥 FIND ROUTE ID (From Routes Table) ---
    $route = \App\Models\Route::where('from_city_id', $fromLocId)
                              ->where('to_city_id', $toLocId)
                              ->first();
    $routeId = $route ? $route->id : null;

    // Fetch Schedules
    $schedules = Schedule::with(['vehicle.vendor', 'stoppages'])
        ->whereHas('vehicle', function($q) use ($type) { $q->where('type', $type); })
        ->whereHas('stoppages', function($q) use ($fromLocId) { $q->where('location_id', $fromLocId); })
        ->whereHas('stoppages', function($q) use ($toLocId) { $q->where('location_id', $toLocId); })
        ->get();

    $availableOptions = [];

    foreach ($schedules as $s) {
        $stoppages = $s->stoppages;
        $boardingData = $stoppages->where('location_id', $fromLocId)->first();
        $destinationData = $stoppages->where('location_id', $toLocId)->first();

        if ($boardingData && $destinationData && $boardingData->stop_order < $destinationData->stop_order) {
            
            $perKm = (float)($s->vehicle->charges_per_km ?? 0);
            $farePerPerson = ($dist > 0) ? ($dist * $perKm) : 0;

            $availableOptions[] = [
                'id'           => $s->id,
                'route_id'     => $routeId, // 🔥 Send route_id from the lookup above
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
    // 1. Validation
    $request->validate([
        'customer_id'   => 'required',
        'total_amount'  => 'required|numeric',
        'paid_amount'   => 'required|numeric',
        'boarding_from' => 'required', // Ye locations hona zaroori hai
        'destination_to'=> 'required',
    ]);

    try {
        // 2. Booking Number Generation (Soft Delete Safe)
        $year = date('Y');
        $lastBooking = \App\Models\Booking::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
        $nextId = $lastBooking ? ($lastBooking->id + 1) : 1;
        $bookingNo = "ST-" . $year . "-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        while (\App\Models\Booking::withTrashed()->where('booking_no', $bookingNo)->exists()) {
            $nextId++;
            $bookingNo = "ST-" . $year . "-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }

        // 3. 🔥 Route & Vehicle Discovery Logic
        $schedule = Schedule::find($request->schedule_id);
        
        // Pehle direct lookup: Agar Routes table mein ye 'From' aur 'To' ka combination hai
        $route = \App\Models\Route::where('from_city_id', $request->boarding_from)
                                  ->where('to_city_id', $request->destination_to)
                                  ->first();
        
        $route_id = $route ? $route->id : ($schedule ? $schedule->route_id : null);
        $vehicle_id = $schedule ? $schedule->vehicle_id : $request->vehicle_id;

        // 4. Calculations
        $total = (float)$request->total_amount;
        $paid  = (float)$request->paid_amount;
        $due   = max(0, $total - $paid);

        // 5. Create Booking
        $booking = Booking::create([
            'booking_no'    => $bookingNo,
            'customer_id'   => $request->customer_id,
            'service_type'  => $request->service_type,
            'route_id'      => $route_id, // Perfect Numeric ID
            'vehicle_id'    => $vehicle_id,
            'travel_date'   => $request->travel_date,
            'pax_count'     => $request->pax_count ?? 1,
            'total_amount'  => $total,
            'paid_amount'   => $paid,
            'due_amount'    => $due,
            'payment_status'=> ($total <= $paid) ? 'Paid' : (($paid > 0) ? 'Partial' : 'Pending'),
            'booking_status'=> 'Upcoming',
        ]);

        return response()->json([
            'status'  => 'success', 
            'message' => 'Booking Confirmed! No: ' . $bookingNo,
            'booking_id' => $booking->id
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

// 1. Single Booking Fetch (For View Modal)
public function show($id)
{
    // Eager loading zaroori hai taaki JS ko customer aur routes ka data mile
    $booking = \App\Models\Booking::with([
        'customer', 
        'passengers', 
        'route.fromCity', 
        'route.toCity', 
        'package', 
        'vehicle'
    ])->findOrFail($id);

    return response()->json(['booking' => $booking]);
}

public function edit($id)
{
    $booking = \App\Models\Booking::with(['route', 'package'])->findOrFail($id);
    return response()->json(['booking' => $booking]);
}
// 2. Edit Page (Optional, Show se bhi kaam chal sakta hai)

// 3. Delete Logic
public function destroy($id)
{
    $booking = Booking::findOrFail($id);
    $booking->delete(); // Soft delete agar enabled hai
    return response()->json(['status' => 'success', 'message' => 'Booking deleted successfully!']);
}


public function printReceipt($id)
{
    $booking = Booking::with(['customer', 'passengers', 'route.fromCity', 'route.toCity', 'vehicle', 'package'])
       ->where('booking_no', $id)               
    ->firstOrFail();
        // dd($booking->toArray());              
    return view('admin.bookings.print', compact('booking'));
}


}
