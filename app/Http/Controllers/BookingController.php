<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\TourPackage;



class BookingController extends Controller
{
   public function index()
{
    $bookings = Booking::with(['customer', 'vehicle', 'route', 'package'])->orderBy('id', 'desc')->get();
    $customers = Customer::where('status', 1)->get();
    $routes = Route::with(['fromCity', 'toCity'])->where('status', 1)->get();
    $packages = TourPackage::where('status', 1)->get();
    $vehicles = \App\Models\Vehicle::with('vendor')->where('status', 1)->get();
    
   $offers = \App\Models\Offer::where('status', 1)
                ->where('valid_until', '>=', date('Y-m-d'))
                ->get();

    return view('admin.bookings.index', compact('bookings', 'customers', 'routes', 'packages', 'vehicles', 'offers'));
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
