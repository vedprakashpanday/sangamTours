<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Vendor;
use App\Models\Amenity;
use App\Models\SeatCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
   public function index() {
    $vehicles = Vehicle::with('vendor')->orderBy('id', 'desc')->get();
    $trashedVehicles = Vehicle::onlyTrashed()->with('vendor')->get();
    $vendors = Vendor::where('status', 1)->get();
    $amenities = Amenity::all(); // Agar amenities table hai toh
    
    // 🔥 Ye line add karein
    $seatCategories =SeatCategory::all(); 

    return view('admin.vehicles.index', compact(
        'vehicles', 
        'trashedVehicles', 
        'vendors', 
        'amenities', 
        'seatCategories'
    ));
}

   public function store(Request $request) {
    // 1. Seat Category wala logic jo humne pehle discuss kiya tha...
    $category = SeatCategory::firstOrCreate([
        'vendor_id'     => $request->vendor_id,
        'category_name' => $request->seat_type 
    ]);

    // 2. Manual Assignment (Isse error kabhi nahi aayega)
    $vehicle = new Vehicle();
    $vehicle->vendor_id      = $request->vendor_id;
    $vehicle->type           = $request->type;
    $vehicle->vehicle_number = $request->vehicle_number;
    $vehicle->total_seats    = $request->total_seats;
    
    // 🔥 Yahan dhyaan dein: Form se 'base_fare' aa raha hai, par DB mein 'charges_per_km' jayega
    $vehicle->charges_per_km = $request->base_fare; 
    
    $vehicle->seat_type      = $request->seat_type;
    $vehicle->status         = 1;
    $vehicle->save();

    // Amenities save karne ka logic...
    if($request->has('amenity_ids')) {
        $vehicle->amenities()->sync($request->amenity_ids);
    }

    return response()->json(['message' => 'Inventory saved successfully!']);
}
    public function edit($id)
    {
        // Amenities ke saath load karein taaki edit modal mein checkbox checked dikhein
        return response()->json(Vehicle::with('amenities')->findOrFail($id));
    }

   public function update(Request $request, $id)
{
    DB::beginTransaction();
    try {
        $vehicle = Vehicle::findOrFail($id);

        // 1. Seat Category check/create logic (Agar user ne seat type badla ho)
        $category = SeatCategory::firstOrCreate([
            'vendor_id'     => $request->vendor_id,
            'category_name' => $request->seat_type 
        ]);

        // 2. Manual Field Mapping
        $vehicle->vendor_id      = $request->vendor_id;
        $vehicle->type           = $request->type;
        $vehicle->vehicle_number = $request->vehicle_number;
        $vehicle->total_seats    = $request->total_seats;
        
        // 🔥 Ye hai main fix: Form se 'base_fare' uthao aur 'charges_per_km' mein daalo
        $vehicle->charges_per_km = $request->base_fare; 
        
        $vehicle->seat_type      = $request->seat_type;
        $vehicle->save();

        // 3. Amenities sync karein
        if($request->has('amenity_ids')) {
            $vehicle->amenities()->sync($request->amenity_ids);
        }

        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'Inventory updated successfully!']);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status' => 'error', 'message' => 'Update failed: ' . $e->getMessage()]);
    }
}
    // --- Trash Logic (Same as before) ---
    public function destroy($id) { Vehicle::findOrFail($id)->delete(); return response()->json(['status' => 'success']); }
    public function restore($id) { Vehicle::withTrashed()->findOrFail($id)->restore(); return response()->json(['status' => 'success']); }
    public function forceDelete($id) { Vehicle::withTrashed()->findOrFail($id)->forceDelete(); return response()->json(['status' => 'success']); }
    public function restoreAll() { Vehicle::onlyTrashed()->restore(); return response()->json(['status' => 'success']); }
    public function emptyTrash() { Vehicle::onlyTrashed()->forceDelete(); return response()->json(['status' => 'success']); }
}