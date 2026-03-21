<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Vehicle;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    // 1. List Page
    public function index() {
        $offers = Offer::orderBy('id', 'desc')->get();
        // Agar aapne SoftDeletes lagaya hai toh trashed items bhi bhej sakte hain
        $trashedOffers = Offer::onlyTrashed()->get(); 
        return view('admin.offers.index', compact('offers', 'trashedOffers'));
    }

    // 2. Store New Offer
    public function store(Request $request) {
        $request->validate([
            'offer_code' => 'required|unique:offers,offer_code',
            'offer_name' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required|numeric',
            'valid_until' => 'required|date',
        ]);

        Offer::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'New Offer Created Successfully!']);
    }

    // 3. Edit - Fetch data for Modal
    public function edit($id) {
        $offer = Offer::findOrFail($id);
        return response()->json($offer);
    }

    // 4. Update Offer
    public function update(Request $request, $id) {
        $offer = Offer::findOrFail($id);
        
        $request->validate([
            'offer_code' => 'required|unique:offers,offer_code,'.$id,
            'offer_name' => 'required',
            'discount_value' => 'required|numeric',
        ]);

        $offer->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Offer Updated Successfully!']);
    }

    // 5. Soft Delete (Move to Trash)
    public function destroy($id) {
        Offer::findOrFail($id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Offer moved to trash!']);
    }

    // 6. Trash Management (Restore/Force Delete)
    public function restore($id) {
        Offer::withTrashed()->findOrFail($id)->restore();
        return response()->json(['status' => 'success', 'message' => 'Offer Restored!']);
    }

    public function forceDelete($id) {
        Offer::withTrashed()->findOrFail($id)->forceDelete();
        return response()->json(['status' => 'success', 'message' => 'Offer deleted permanently!']);
    }

    // 7. 🔥 AJAX Logic: Fetch specific items based on category
    public function getItemsByCategory($category)
    {
        if ($category === 'Tour Package') {
            // Tour Packages ka data uthao
            $items = TourPackage::select('id', 'title as name')
                                ->where('status', 1)
                                ->get();
        } else {
            // Flight, Bus, ya Train ka data Vehicles table se uthao
            $items = Vehicle::with('vendor')
                ->where('type', $category)
                ->where('status', 1)
                ->get()
                ->map(function($v) {
                    return [
                        'id' => $v->id,
                        'name' => $v->vendor->name . ' - ' . $v->vehicle_number
                    ];
                });
        }

        return response()->json($items);
    }
}