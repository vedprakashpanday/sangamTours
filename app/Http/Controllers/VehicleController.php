<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Vendor;
use App\Models\Amenity;
use App\Models\SeatCategory;
use App\Models\CommonImage; // 🔥 Image model import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // 🔥 File facade import

class VehicleController extends Controller
{
    public function index() {
        // 🔥 'images' relationship load karna mat bhulna UI mein dikhane ke liye
        $vehicles = Vehicle::with(['vendor', 'images'])->orderBy('id', 'desc')->get();
        $trashedVehicles = Vehicle::onlyTrashed()->with(['vendor', 'images'])->get();
        
        $vendors = Vendor::where('status', 1)->get();
        $amenities = Amenity::all(); 
        $seatCategories = SeatCategory::all(); 

        return view('admin.vehicles.index', compact(
            'vehicles', 
            'trashedVehicles', 
            'vendors', 
            'amenities', 
            'seatCategories'
        ));
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {
            // Seat Category logic
            $category = SeatCategory::firstOrCreate([
                'vendor_id'     => $request->vendor_id,
                'category_name' => $request->seat_type 
            ]);

            // Save Vehicle
            $vehicle = new Vehicle();
            $vehicle->vendor_id      = $request->vendor_id;
            $vehicle->type           = $request->type;
            $vehicle->vehicle_number = $request->vehicle_number;
            $vehicle->total_seats    = $request->total_seats;
            $vehicle->charges_per_km = $request->base_fare; 
            $vehicle->seat_type      = $request->seat_type;
          
            $vehicle->model_name     = $request->model_name;
            $vehicle->luggage_allowed = $request->luggage_allowed;
            $vehicle->status         = 1;
            $vehicle->save();

            // Save Amenities
            if($request->has('amenity_ids')) {
                $vehicle->amenities()->sync($request->amenity_ids);
            }

            // 🔥 NEW: Image Upload Logic
            if ($request->hasFile('main_image')) {
                $this->uploadImage($request->file('main_image'), $vehicle, 'main');
            }

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $img) {
                    $this->uploadImage($img, $vehicle, 'gallery');
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Inventory saved successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        // 🔥 'images' bhi load karein preview ke liye
        return response()->json(Vehicle::with(['amenities', 'images'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $vehicle = Vehicle::findOrFail($id);

            // Seat Category
            $category = SeatCategory::firstOrCreate([
                'vendor_id'     => $request->vendor_id,
                'category_name' => $request->seat_type 
            ]);

            // Update Vehicle Fields
            $vehicle->vendor_id      = $request->vendor_id;
            $vehicle->type           = $request->type;
            $vehicle->vehicle_number = $request->vehicle_number;
            $vehicle->total_seats    = $request->total_seats;
            $vehicle->charges_per_km = $request->base_fare; 
            $vehicle->seat_type      = $request->seat_type;
            $vehicle->model_name     = $request->model_name;
            $vehicle->luggage_allowed = $request->luggage_allowed;
            $vehicle->save();

            // Sync Amenities
            if($request->has('amenity_ids')) {
                $vehicle->amenities()->sync($request->amenity_ids);
            } else {
                $vehicle->amenities()->detach(); // Agar saare checkbox clear kar diye hon
            }

            // 🔥 NEW: Handle Deleted Images (Cross dabane par)
            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $imgId) {
                    $img = CommonImage::find($imgId);
                    if ($img) {
                        $folder = $img->image_type == 'main' ? 'vehicles' : 'vehicles/gallery';
                        $path = public_path('uploads/' . $folder . '/' . $img->filename);
                        if (File::exists($path)) File::delete($path);
                        $img->delete();
                    }
                }
            }

            // 🔥 NEW: Handle New Main Image
            if ($request->hasFile('main_image')) {
                $oldMain = $vehicle->images()->where('image_type', 'main')->first();
                if($oldMain) {
                    $oldPath = public_path('uploads/vehicles/' . $oldMain->filename);
                    if(File::exists($oldPath)) File::delete($oldPath);
                    $oldMain->delete();
                }
                $this->uploadImage($request->file('main_image'), $vehicle, 'main');
            }

            // 🔥 NEW: Handle New Gallery Images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $img) {
                    $this->uploadImage($img, $vehicle, 'gallery');
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Inventory updated successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    // --- Trash Logic ---
    public function destroy($id) 
    { 
        Vehicle::findOrFail($id)->delete(); 
        return response()->json(['status' => 'success']); 
    }

    public function restore($id) 
    { 
        Vehicle::withTrashed()->findOrFail($id)->restore(); 
        return response()->json(['status' => 'success']); 
    }

    public function restoreAll() 
    { 
        Vehicle::onlyTrashed()->restore(); 
        return response()->json(['status' => 'success']); 
    }

    // 🔥 NEW: Permanent Delete (Image Server se Hatao)
    public function forceDelete($id) 
    { 
        $vehicle = Vehicle::withTrashed()->with('images')->findOrFail($id);
        
        foreach ($vehicle->images as $img) {
            $folder = ($img->image_type == 'main') ? 'vehicles' : 'vehicles/gallery';
            $path = public_path('uploads/' . $folder . '/' . $img->filename);
            if (File::exists($path)) File::delete($path);
        }
        
        $vehicle->images()->delete(); // DB se delete
        $vehicle->forceDelete(); 
        return response()->json(['status' => 'success']); 
    }

    public function emptyTrash() 
    { 
        $trashed = Vehicle::onlyTrashed()->get();
        foreach($trashed as $vehicle) {
            $this->forceDelete($vehicle->id);
        }
        return response()->json(['status' => 'success']); 
    }

    // 🔥 NEW: Image Upload Helper
    private function uploadImage($file, $model, $type)
    {
        // Vehicles folder create zaroor kar lena public/uploads ke andar
        $folder = $type == 'main' ? 'vehicles' : 'vehicles/gallery';
        
        // Folder check and create agar nahi hai
        $path = public_path('uploads/' . $folder);
        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        }

        $name = rand(1000, 9999) . time() . '.' . $file->getClientOriginalExtension();
        $file->move($path, $name);
        
        $model->images()->create([
            'filename' => $name,
            'image_type' => $type
        ]);
    }
}