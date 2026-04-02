<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccommodationType;
use Illuminate\Http\Request;

class AccommodationTypeController extends Controller
{
    public function index() {
        $types = AccommodationType::latest()->get();
        
        // Common Icons ki list (Boxicons classes)
        $icons = [
            'bx-hotel', 'bx-home', 'bx-building', 'bx-bed', 'bx-vignette', 
            'bx-bus', 'bx-car', 'bx-train', 'bx-plane', 'bx-map-pin', 
            'bx-restaurant', 'bx-coffee', 'bx-wifi', 'bx-swim'
        ];

        return view('admin.accommodations.types.index', compact('types', 'icons'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:accommodation_types,name',
            'icon' => 'nullable|string' // 🔥 Icon validation add ki
        ]);

        AccommodationType::create([
            'name' => $request->name,
            'icon' => $request->icon  // 🔥 Database mein icon save karne ke liye
        ]);

        return back()->with('success', 'Accommodation Type added!');
    }

    public function update(Request $request, $id) {
        $request->validate([
            // 🔥 ID ko ignore karna zaroori hai update ke waqt, warna error aayega
            'name' => 'required|unique:accommodation_types,name,' . $id, 
            'icon' => 'nullable|string' 
        ]);

        $type = AccommodationType::findOrFail($id);
        
        $type->update([
            'name' => $request->name,
            'icon' => $request->icon // 🔥 Update ke waqt icon save karna
        ]);

        return back()->with('success', 'Updated successfully!');
    }

    public function destroy($id) {
        AccommodationType::findOrFail($id)->delete();
        return back()->with('success', 'Deleted successfully!');
    }
}