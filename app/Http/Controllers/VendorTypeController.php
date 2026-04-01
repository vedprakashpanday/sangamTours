<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VendorType;
use Illuminate\Http\Request;

class VendorTypeController extends Controller
{
    public function index() {
        $types = VendorType::latest()->get();
        return view('admin.vendors.types.index', compact('types'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:vendor_types,name']);
        
        VendorType::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Vendor Type saved successfully!');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|unique:vendor_types,name,' . $id
    ]);

    $type = VendorType::findOrFail($id);
    $type->update(['name' => $request->name]);

    return back()->with('success', 'Updated successfully!');
}

public function destroy($id)
{
    VendorType::findOrFail($id)->delete();
    return back()->with('success', 'Deleted successfully!');
}
}