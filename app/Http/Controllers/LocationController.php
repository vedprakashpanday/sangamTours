<?php
namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    // List all locations (DataTable ke liye)
    public function index()
    {
        $locations = Location::orderBy('id', 'desc')->get();
        return view('admin.locations.index', compact('locations'));
    }

    // Store New Location
    public function store(Request $request)
    {
        $request->validate([
            'country_name'  => 'required|string',
            'state_name'    => 'required|string',
            'city_location' => 'required|string|max:255',
        ]);

        try {
            Location::create([
                'country_name'  => $request->country_name,
                'state_name'    => $request->state_name,
                'city_location' => $request->city_location,
                'status'        => 1
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Location added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    // Fetch data for Edit Modal
    public function edit($id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['status' => 'error', 'message' => 'Data not found'], 404);
        }
        return response()->json($location);
    }

    // Update Location
    public function update(Request $request, $id)
    {
        $request->validate([
            'country_name'  => 'required',
            'state_name'    => 'required',
            'city_location' => 'required',
        ]);

        try {
            $location = Location::findOrFail($id);
            $location->update([
                'country_name'  => $request->country_name,
                'state_name'    => $request->state_name,
                'city_location' => $request->city_location,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Location updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Update failed!'
            ], 500);
        }
    }

    // Delete Location
    public function destroy($id)
    {
        try {
            $location = Location::findOrFail($id);
            $location->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Location deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Delete failed!'
            ], 500);
        }
    }
}