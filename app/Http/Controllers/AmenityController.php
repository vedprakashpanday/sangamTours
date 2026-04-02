<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function index() {
        $amenities = Amenity::latest()->get();
        
        // Amenities ke hisaab se icons ki list
        $icons = [
            'bx-wifi', 'bx-swim', 'bx-car', 'bx-coffee', 'bx-restaurant', 
            'bx-tv', 'bx-wind', 'bx-bath', 'bx-dumbbell', 'bx-spa', 
            'bx-cctv', 'bx-accessibility', 'bx-drink', 'bx-plug'
        ];

        return view('admin.accommodations.amenities.index', compact('amenities', 'icons'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:amenities,name',
            'icon' => 'nullable|string'
        ]);

        Amenity::create([
            'name' => $request->name,
            'icon' => $request->icon
        ]);

        return back()->with('success', 'Amenity added successfully!');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|unique:amenities,name,' . $id,
            'icon' => 'nullable|string'
        ]);

        Amenity::findOrFail($id)->update([
            'name' => $request->name,
            'icon' => $request->icon
        ]);

        return back()->with('success', 'Amenity updated successfully!');
    }

    public function destroy($id) {
        Amenity::findOrFail($id)->delete();
        return back()->with('success', 'Amenity deleted!');
    }
}