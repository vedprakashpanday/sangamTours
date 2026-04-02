<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Image delete karne ke liye zaroori hai

class VendorController extends Controller
{
    public function index() {
        $vendors = Vendor::with('vendorType')->latest()->get();
        $trashedVendors = Vendor::onlyTrashed()->with('vendorType')->get();
        $vendorTypes = VendorType::where('status', 1)->orderBy('name', 'asc')->get();

        return view('admin.vendors.index', compact('vendors', 'trashedVendors', 'vendorTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required',
            'vendor_type_id' => 'required|exists:vendor_types,id',
            'phone'          => 'required|unique:vendors,phone',
            'email'          => 'nullable|email',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Image validation
        ]);

        // Image Upload Logic
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/vendors'), $imageName);
        }

        Vendor::create([
            'name'           => $request->name,
            'vendor_type_id' => $request->vendor_type_id,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'image'          => $imageName, // Save image name to DB
            'is_api'         => $request->has('is_api') ? 1 : 0,
            'status'         => $request->has('status') ? 1 : 0,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Vendor Added Successfully!']);
    }

    public function edit($id)
    {
        return response()->json(Vendor::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'name'           => 'required',
            'vendor_type_id' => 'required|exists:vendor_types,id',
            'phone'          => 'required|unique:vendors,phone,' . $id,
            'email'          => 'nullable|email',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imageName = $vendor->image; // Purani image default rakhein

        // Agar nayi image aayi hai toh purani delete karein aur nayi upload karein
        if ($request->hasFile('image')) {
            if ($imageName && File::exists(public_path('uploads/vendors/' . $imageName))) {
                File::delete(public_path('uploads/vendors/' . $imageName));
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/vendors'), $imageName);
        }

        $vendor->update([
            'name'           => $request->name,
            'vendor_type_id' => $request->vendor_type_id,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'image'          => $imageName,
            'is_api'         => $request->has('is_api') ? 1 : 0,
            'status'         => $request->has('status') ? 1 : 0,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Vendor Updated Successfully!']);
    }

    // --- MISSING TRASH METHODS ---

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete(); // Soft delete
        return response()->json(['status' => 'success', 'message' => 'Vendor moved to trash!']);
    }

    public function restore($id)
    {
        Vendor::withTrashed()->findOrFail($id)->restore();
        return response()->json(['status' => 'success', 'message' => 'Vendor restored!']);
    }

    public function forceDelete($id)
    {
        $vendor = Vendor::withTrashed()->findOrFail($id);
        
        // Server se actual image delete karna
        if ($vendor->image && File::exists(public_path('uploads/vendors/' . $vendor->image))) {
            File::delete(public_path('uploads/vendors/' . $vendor->image));
        }

        $vendor->forceDelete(); // Permanent delete
        return response()->json(['status' => 'success', 'message' => 'Vendor permanently deleted!']);
    }

    public function restoreAll()
    {
        Vendor::onlyTrashed()->restore();
        return response()->json(['status' => 'success', 'message' => 'All vendors restored!']);
    }

    public function emptyTrash()
    {
        $trashedVendors = Vendor::onlyTrashed()->get();
        
        // Loop chalakar delete karna taaki sabki images bhi server se remove ho jayein
        foreach ($trashedVendors as $vendor) {
            if ($vendor->image && File::exists(public_path('uploads/vendors/' . $vendor->image))) {
                File::delete(public_path('uploads/vendors/' . $vendor->image));
            }
            $vendor->forceDelete();
        }

        return response()->json(['status' => 'success', 'message' => 'Trash cleared successfully!']);
    }
}