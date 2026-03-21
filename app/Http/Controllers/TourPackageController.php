<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourPackage;
use App\Models\CommonImage;
use App\Models\PackageStay;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TourPackageController extends Controller
{
    // 1. Display List (Active aur Trashed dono)
    public function index()
    {
        $packages = TourPackage::with(['location', 'images'])->orderBy('id', 'desc')->get();
        
        // Sirf Trashed data ke liye
        $trashedPackages = TourPackage::onlyTrashed()->with(['location', 'images'])->orderBy('id', 'desc')->get();
        
        // Dropdown ke liye distinct countries
        $countries = Location::select('country_name')->distinct()->get();
        
        return view('admin.packages.index', compact('packages', 'trashedPackages', 'countries'));
    }

    // 2. Package Store Logic
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $customId = 'PKG-' . date('Y') . '-' . rand(1000, 9999);

            $package = new TourPackage();
            $package->package_id = $customId;
            $package->title = $request->title;
            $package->price = $request->price;
            $package->discount_price = $request->discount_price;
            $package->details = $request->details;
            $package->location_id = $request->state_id; 
            $package->status = $request->has('status') ? 1 : 0;
            $package->save();

            if ($request->hasFile('main_image')) {
                $this->uploadImage($request->file('main_image'), $package, 'main');
            }

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $img) {
                    $this->uploadImage($img, $package, 'gallery');
                }
            }

            $this->saveStays($request, $package);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Package Created Successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 3. Package Edit Logic
    public function edit($id)
    {
        $package = TourPackage::with(['images', 'stays', 'location'])->findOrFail($id);
        return response()->json($package);
    }

    // 4. Package Update Logic
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $package = TourPackage::findOrFail($id);
            
            $package->update([
                'title' => $request->title,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'details' => $request->details,
                'location_id' => $request->state_id,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            // UI se hatayi gayi images ko unlink aur delete karna
            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $imgId) {
                    $img = CommonImage::find($imgId);
                    if ($img) {
                        $folder = $img->image_type == 'main' ? 'packages' : 'packages/gallery';
                        $path = public_path('uploads/' . $folder . '/' . $img->filename);
                        if (File::exists($path)) File::delete($path);
                        $img->delete();
                    }
                }
            }

            // Nayi Main Image Handle karna
            if ($request->hasFile('main_image')) {
                $oldMain = $package->images()->where('image_type', 'main')->first();
                if($oldMain) {
                    $oldPath = public_path('uploads/packages/' . $oldMain->filename);
                    if(File::exists($oldPath)) File::delete($oldPath);
                    $oldMain->delete();
                }
                $this->uploadImage($request->file('main_image'), $package, 'main');
            }

            // Nayi Gallery Images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $img) {
                    $this->uploadImage($img, $package, 'gallery');
                }
            }

            // Stays Update
            $package->stays()->delete();
            $this->saveStays($request, $package);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Package Updated Successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 5. Soft Delete (Trash mein bhejna)
    public function destroy($id)
    {
        $package = TourPackage::findOrFail($id);
        $package->delete(); 
        return response()->json(['status' => 'success', 'message' => 'Package moved to trash.']);
    }

    // 6. Restore Logic
    public function restore($id)
    {
        $package = TourPackage::withTrashed()->findOrFail($id);
        $package->restore();
        return response()->json(['status' => 'success', 'message' => 'Package restored successfully!']);
    }

    // 7. Force Delete (Permanent delete with Image Unlink)
    public function forceDelete($id)
    {
        DB::beginTransaction();
        try {
            $package = TourPackage::withTrashed()->with(['images', 'stays'])->findOrFail($id);
            
            // Images unlink karein
            foreach ($package->images as $img) {
                $folder = ($img->image_type == 'main') ? 'packages' : 'packages/gallery';
                $path = public_path('uploads/' . $folder . '/' . $img->filename);
                if (File::exists($path)) File::delete($path);
            }

            $package->images()->delete();
            $package->stays()->delete();
            $package->forceDelete(); 

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Package permanently deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 8. Restore All Trashed Items
    public function restoreAll()
    {
        TourPackage::onlyTrashed()->restore();
        return response()->json(['status' => 'success', 'message' => 'All packages restored successfully.']);
    }

    // 9. Empty Trash Logic
    public function emptyTrash()
    {
        $trashed = TourPackage::onlyTrashed()->get();
        foreach ($trashed as $package) {
            $this->forceDelete($package->id);
        }
        return response()->json(['status' => 'success', 'message' => 'Trash cleared successfully.']);
    }

    // --- Private Helpers ---

    private function uploadImage($file, $model, $type)
    {
        $folder = $type == 'main' ? 'packages' : 'packages/gallery';
        $name = rand(100, 999) . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/' . $folder), $name);
        
        $model->images()->create([
            'filename' => $name,
            'image_type' => $type
        ]);
    }

    private function saveStays($request, $package)
    {
        if ($request->days) {
            foreach ($request->days as $key => $val) {
                if (!empty($val)) {
                    $package->stays()->create([
                        'days' => $request->days[$key],
                        'nights' => $request->nights[$key],
                        'place_description' => $request->place[$key],
                    ]);
                }
            }
        }
    }

    // --- Dropdown API Methods ---

    public function getStatesByCountry($countryName)
    {
        $states = Location::where('country_name', $countryName)
                    ->select('state_name')
                    ->distinct()
                    ->get();
        return response()->json($states);
    }

    public function getCitiesByState($stateName)
    {
        $cities = Location::where('state_name', $stateName)
                    ->select('id', 'city_location')
                    ->get();
        return response()->json($cities);
    }
}