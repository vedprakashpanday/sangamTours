<?php


namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Location;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        // Yahan 'fromCity' aur 'toCity' relationships load karenge
        $routes = Route::with(['fromCity', 'toCity'])->orderBy('id', 'desc')->get();
        $trashedRoutes = Route::onlyTrashed()->with(['fromCity', 'toCity'])->get();
        $cities = Location::all(); // Dropdown ke liye

        return view('admin.routes.index', compact('routes', 'trashedRoutes', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_city_id' => 'required|different:to_city_id',
            'to_city_id' => 'required',
        ], [
            'from_city_id.different' => 'From and To city cannot be the same!'
        ]);

        Route::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'Route Created!']);
    }

    public function edit($id)
    {
        return response()->json(Route::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        Route::findOrFail($id)->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Route Updated!']);
    }

    // ... Trash/Restore logic same as previous controllers ...
}