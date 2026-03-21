<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::orderBy('id', 'desc')->get();
        $trashedVendors = Vendor::onlyTrashed()->orderBy('id', 'desc')->get();
        return view('admin.vendors.index', compact('vendors', 'trashedVendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'phone' => 'required|unique:vendors,phone',
        ]);

        Vendor::create([
            'name' => $request->name,
            'type' => $request->type,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_api' => $request->has('is_api') ? 1 : 0,
            'status' => $request->has('status') ? 1 : 0,
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
        $vendor->update([
            'name' => $request->name,
            'type' => $request->type,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_api' => $request->has('is_api') ? 1 : 0,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Vendor Updated Successfully!']);
    }

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Vendor moved to trash!']);
    }

    public function restore($id)
    {
        Vendor::withTrashed()->findOrFail($id)->restore();
        return response()->json(['status' => 'success', 'message' => 'Vendor restored!']);
    }

    public function forceDelete($id)
    {
        Vendor::withTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['status' => 'success', 'message' => 'Vendor permanently deleted!']);
    }

    public function restoreAll()
    {
        Vendor::onlyTrashed()->restore();
        return response()->json(['status' => 'success', 'message' => 'All vendors restored!']);
    }

    public function emptyTrash()
    {
        Vendor::onlyTrashed()->forceDelete();
        return response()->json(['status' => 'success', 'message' => 'Trash cleared!']);
    }
}