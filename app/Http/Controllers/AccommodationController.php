<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\CommonImage;
use App\Models\Location;
use App\Models\AccommodationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Amenity; 

class AccommodationController extends Controller
{
    // 1. Display List
    public function index()
    {
        $accommodations = Accommodation::with(['location', 'images', 'accommodationType'])->orderBy('id', 'desc')->get();
        
        $trashedAccommodations = Accommodation::onlyTrashed()->with(['location', 'images', 'accommodationType'])->orderBy('id', 'desc')->get();
        
        $countries = Location::select('country_name')->distinct()->get();
        
        $types = AccommodationType::where('status', 1)->get();
        $amenities = Amenity::where('status', 1)->get(); 
        
        return view('admin.accommodations.index', compact('accommodations', 'trashedAccommodations', 'countries', 'types', 'amenities'));
    }

    public function create() 
    {
        $types = AccommodationType::where('status', 1)->get();
        $countries = Location::select('country_name')->distinct()->get();
        return view('admin.accommodations.create', compact('types', 'countries'));
    }

    // 2. Store Logic
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'accommodation_type_id' => 'required|exists:accommodation_types,id',
            'star_rating' => 'required',
            'price' => 'required|numeric',
            'state_id' => 'required|exists:locations,id',
            'main_image' => 'required|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $customId = 'ACC-' . date('Y') . '-' . rand(1000, 9999);

            $accommodation = new Accommodation();
            $accommodation->accommodation_id = $customId;
            $accommodation->name = $request->name;
            $accommodation->accommodation_type_id = $request->accommodation_type_id; 
            $accommodation->star_rating = $request->star_rating;
            $accommodation->price_per_night = $request->price;
            $accommodation->description = $request->description;
            $accommodation->location_id = $request->state_id;
            $accommodation->status = $request->has('status') ? 1 : 0;
            $accommodation->save();

            // 🔥 NEW: Save Amenities using sync
            if ($request->has('amenities')) {
                $accommodation->amenities()->sync($request->amenities);
            }

            if ($request->hasFile('main_image')) {
                $this->uploadImage($request->file('main_image'), $accommodation, 'main');
            }

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
        // 🔥 NEW: 'amenities' ko bhi load kiya gaya hai taaki frontend par check ho sake
        $accommodation = Accommodation::with(['images', 'location', 'accommodationType', 'amenities'])->findOrFail($id);
        return response()->json($accommodation);
    }

    // 4. Update Logic
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'accommodation_type_id' => 'required|exists:accommodation_types,id',
            'price' => 'required|numeric',
            'state_id' => 'required|exists:locations,id',
        ]);

        DB::beginTransaction();
        try {
            $accommodation = Accommodation::findOrFail($id);
            
            $accommodation->update([
                'name' => $request->name,
                'accommodation_type_id' => $request->accommodation_type_id,
                'price_per_night' => $request->price,
                'description' => $request->description,
                'location_id' => $request->state_id,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            // 🔥 NEW: Update Amenities (sync ensures old ones are removed and new ones are added)
            if ($request->has('amenities')) {
                $accommodation->amenities()->sync($request->amenities);
            } else {
                // Agar user ne saare checkboxes uncheck kar diye
                $accommodation->amenities()->detach();
            }

            // Delete selected images
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

    public function destroy($id)
    {
        Accommodation::findOrFail($id)->delete(); 
        return response()->json(['status' => 'success', 'message' => 'Accommodation moved to trash.']);
    }

    public function restore($id)
    {
        Accommodation::withTrashed()->findOrFail($id)->restore();
        return response()->json(['status' => 'success', 'message' => 'Accommodation restored successfully!']);
    }

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
            
            // Note: Pivot table records (amenities link) automatically delete ho jayenge 
            // kyunki humne migration mein `onDelete('cascade')` lagaya tha.
            $accommodation->forceDelete(); 

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Accommodation permanently deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

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