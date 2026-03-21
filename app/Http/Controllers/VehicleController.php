<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Vendor;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['vendor', 'amenities'])->orderBy('id', 'desc')->get();
        $trashedVehicles = Vehicle::onlyTrashed()->with('vendor')->get();
        
        // Form ke dropdowns ke liye
        $vendors = Vendor::where('status', 1)->get(); 
        $amenities = Amenity::all(); // Make sure you have some data in amenities table

        return view('admin.vehicles.index', compact('vehicles', 'trashedVehicles', 'vendors', 'amenities'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $vehicle = Vehicle::create($request->except('amenity_ids'));
            
            // Many-to-Many relationship (Pivot table)
            if($request->has('amenity_ids')) {
                $vehicle->amenities()->attach($request->amenity_ids);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Vehicle Inventory Created!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
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
            $vehicle->update($request->except('amenity_ids'));

            // Sync amenities (Purani hatayega, nayi jodega)
            if($request->has('amenity_ids')) {
                $vehicle->amenities()->sync($request->amenity_ids);
            } else {
                $vehicle->amenities()->detach();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Inventory Updated!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // --- Trash Logic (Same as before) ---
    public function destroy($id) { Vehicle::findOrFail($id)->delete(); return response()->json(['status' => 'success']); }
    public function restore($id) { Vehicle::withTrashed()->findOrFail($id)->restore(); return response()->json(['status' => 'success']); }
    public function forceDelete($id) { Vehicle::withTrashed()->findOrFail($id)->forceDelete(); return response()->json(['status' => 'success']); }
    public function restoreAll() { Vehicle::onlyTrashed()->restore(); return response()->json(['status' => 'success']); }
    public function emptyTrash() { Vehicle::onlyTrashed()->forceDelete(); return response()->json(['status' => 'success']); }
}