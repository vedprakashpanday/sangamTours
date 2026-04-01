<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
   public function index() {
    // 1. Active Vendors with relationship
    $vendors = Vendor::with('vendorType')->latest()->get();
    
    // 2. Trashed Vendors with relationship (Ye miss tha)
    $trashedVendors = Vendor::onlyTrashed()->with('vendorType')->get();
    
    // 3. Dropdown ke liye types
    $vendorTypes = VendorType::where('status', 1)->orderBy('name', 'asc')->get();

    return view('admin.vendors.index', compact('vendors', 'trashedVendors', 'vendorTypes'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'vendor_type_id' => 'required|exists:vendor_types,id', // 'type' ki jagah sirf ID use karein
            'phone' => 'required|unique:vendors,phone',
            'email' => 'nullable|email',
        ]);

        Vendor::create([
            'name'           => $request->name,
            'vendor_type_id' => $request->vendor_type_id, // Foreign Key save hogi
            'phone'          => $request->phone,
            'email'          => $request->email,
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
            'name' => 'required',
            'vendor_type_id' => 'required|exists:vendor_types,id',
            'phone' => 'required|unique:vendors,phone,' . $id,
            'email' => 'nullable|email',
        ]);

        $vendor->update([
            'name'           => $request->name,
            'vendor_type_id' => $request->vendor_type_id,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'is_api'         => $request->has('is_api') ? 1 : 0,
            'status'         => $request->has('status') ? 1 : 0,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Vendor Updated Successfully!']);
    }

    // ... baaki functions (destroy, restore, etc.) bilkul sahi hain aapke
}