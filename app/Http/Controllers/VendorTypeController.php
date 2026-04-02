<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VendorType;
use Illuminate\Http\Request;

class VendorTypeController extends Controller
{
    public function index() {
        $types = VendorType::latest()->get();
        
        // Thode aur useful icons add kar diye hain
    $icons = [
    // 🚗 Transport & Vehicles (Plane ke liye bxs- use kiya hai)
    'bx-car', 'bx-taxi', 'bx-bus', 'bx-cycling', 'bx-train', 
    'bxs-plane', 'bxs-plane-alt', 'bxs-plane-take-off', 'bxs-plane-land', // 🔥 Yahan change kiya hai

    // 🍔 Food, Restaurant & Fast Food
    'bx-restaurant', 'bx-bowl-hot', 'bx-dish', 'bx-coffee-togo', 
    'bx-drink', 'bx-baguette', 'bx-cake', 'bx-food-menu',

    // 🏨 Hotels, Stays & Places
    'bx-hotel', 'bx-building-house', 'bx-store-alt', 'bx-bed', 

    // 🌍 Travel & Tours
    'bx-trip', 'bx-map-alt', 'bx-globe', 'bx-briefcase'
];
        
        return view('admin.vendors.types.index', compact('types', 'icons'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:vendor_types,name',
            'icon' => 'nullable|string' // 🔥 Icon validation
        ]);
        
        VendorType::create([
            'name' => $request->name,
            'icon' => $request->icon  // 🔥 Database mein icon save
        ]);

        return back()->with('success', 'Vendor Type saved successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:vendor_types,name,' . $id,
            'icon' => 'nullable|string' // 🔥 Icon validation
        ]);

        $type = VendorType::findOrFail($id);
        
        $type->update([
            'name' => $request->name,
            'icon' => $request->icon // 🔥 Update ke waqt icon save
        ]);

        return back()->with('success', 'Updated successfully!');
    }

    public function destroy($id)
    {
        VendorType::findOrFail($id)->delete();
        return back()->with('success', 'Deleted successfully!');
    }
}