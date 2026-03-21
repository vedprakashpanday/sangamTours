<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accommodation; // Accommodation Model
use App\Models\CommonImage;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AccommodationController extends Controller
{
    // 1. Display List (Active aur Trashed dono)
    public function index()
    {
        $accommodations = Accommodation::with(['location', 'images'])->orderBy('id', 'desc')->get();
        
        // Trashed (Deleted) hotels ke liye
        $trashedAccommodations = Accommodation::onlyTrashed()->with(['location', 'images'])->orderBy('id', 'desc')->get();
        
        // Dropdown ke liye countries
        $countries = Location::select('country_name')->distinct()->get();
        
        return view('admin.accommodations.index', compact('accommodations', 'trashedAccommodations', 'countries'));
    }

    // 2. Store Logic
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Unique ID for Accommodation
            $customId = 'ACC-' . date('Y') . '-' . rand(1000, 9999);

            $accommodation = new Accommodation();
            $accommodation->accommodation_id = $customId; // Agar aapne table mein rakha hai
            $accommodation->name = $request->name;
            $accommodation->hotel_type = $request->hotel_type; // e.g. 3 Star, Resort
            $accommodation->price_per_night = $request->price;
            $accommodation->description = $request->description;
            $accommodation->location_id = $request->state_id; // state_id hi location_id hai
            $accommodation->status = $request->has('status') ? 1 : 0;
            $accommodation->save();

            // Main Image Save
            if ($request->hasFile('main_image')) {
                $this->uploadImage($request->file('main_image'), $accommodation, 'main');
            }

            // Gallery Images Save
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $img) {
                    $this->uploadImage($img, $accommodation, 'gallery');
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Accommodation Created Successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 3. Edit Logic
    public function edit($id)
    {
        $accommodation = Accommodation::with(['images', 'location'])->findOrFail($id);
        return response()->json($accommodation);
    }

    // 4. Update Logic
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $accommodation = Accommodation::findOrFail($id);
            
            $accommodation->update([
                'name' => $request->name,
                'hotel_type' => $request->hotel_type,
                'price_per_night' => $request->price,
                'description' => $request->description,
                'location_id' => $request->state_id,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            // Deleted Images handling
            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $imgId) {
                    $img = CommonImage::find($imgId);
                    if ($img) {
                        $folder = $img->image_type == 'main' ? 'accommodations' : 'accommodations/gallery';
                        $path = public_path('uploads/' . $folder . '/' . $img->filename);
                        if (File::exists($path)) File::delete($path);
                        $img->delete();
                    }
                }
            }

            // Main Image Update
            if ($request->hasFile('main_image')) {
                $oldMain = $accommodation->images()->where('image_type', 'main')->first();
                if($oldMain) {
                    $oldPath = public_path('uploads/accommodations/' . $oldMain->filename);
                    if(File::exists($oldPath)) File::delete($oldPath);
                    $oldMain->delete();
                }
                $this->uploadImage($request->file('main_image'), $accommodation, 'main');
            }

            // Gallery Images Add
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $img) {
                    $this->uploadImage($img, $accommodation, 'gallery');
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Accommodation Updated Successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 5. Soft Delete (Move to Trash)
    public function destroy($id)
    {
        $accommodation = Accommodation::findOrFail($id);
        $accommodation->delete(); 
        return response()->json(['status' => 'success', 'message' => 'Accommodation moved to trash.']);
    }

    // 6. Restore Logic
    public function restore($id)
    {
        $accommodation = Accommodation::withTrashed()->findOrFail($id);
        $accommodation->restore();
        return response()->json(['status' => 'success', 'message' => 'Accommodation restored successfully!']);
    }

    // 7. Force Delete (Permanent + Unlink)
    public function forceDelete($id)
    {
        DB::beginTransaction();
        try {
            $accommodation = Accommodation::withTrashed()->with(['images'])->findOrFail($id);
            
            foreach ($accommodation->images as $img) {
                $folder = ($img->image_type == 'main') ? 'accommodations' : 'accommodations/gallery';
                $path = public_path('uploads/' . $folder . '/' . $img->filename);
                if (File::exists($path)) File::delete($path);
            }

            $accommodation->images()->delete();
            $accommodation->forceDelete(); 

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Accommodation permanently deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 8. Bulk Actions
    public function restoreAll()
    {
        Accommodation::onlyTrashed()->restore();
        return response()->json(['status' => 'success', 'message' => 'All items restored.']);
    }

    public function emptyTrash()
    {
        $trashed = Accommodation::onlyTrashed()->get();
        foreach ($trashed as $item) {
            $this->forceDelete($item->id);
        }
        return response()->json(['status' => 'success', 'message' => 'Trash cleared.']);
    }

    // --- Private Helpers ---
    private function uploadImage($file, $model, $type)
    {
        $folder = $type == 'main' ? 'accommodations' : 'accommodations/gallery';
        $name = rand(100, 999) . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/' . $folder), $name);
        
        $model->images()->create([
            'filename' => $name,
            'image_type' => $type
        ]);
    }

    // --- Dropdowns (Same as Package) ---
    public function getStatesByCountry($countryName)
    {
        $states = Location::where('country_name', $countryName)->select('state_name')->distinct()->get();
        return response()->json($states);
    }

    public function getCitiesByState($stateName)
    {
        $cities = Location::where('state_name', $stateName)->select('id', 'city_location')->get();
        return response()->json($cities);
    }
}