<?php
namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleStoppage;
use App\Models\Vehicle;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ScheduleController extends Controller
{
   public function index() {
    // route.fromCity aur route.toCity tabhi load honge jab route exist karega
    $schedules = Schedule::with([
        'vehicle.vendor', 
        'stoppages.location', 
        'route.fromCity', 
        'route.toCity'
    ])->orderBy('id', 'desc')->get();

    $vehicles = Vehicle::with('vendor')->where('status', 1)->get();
    $locations = \App\Models\Location::where('status', 1)->get(); 

    return view('admin.schedules.index', compact('schedules', 'vehicles', 'locations'));
}
// 1. View Full Path (Timeline Modal ke liye)
public function show($id) {
    // Stoppages ko unke location ke saath load karo
    $schedule = Schedule::with('stoppages.location')->findOrFail($id);
    return response()->json($schedule);
}

// 2. Edit Page (Modal mein data bharne ke liye)
public function edit($id) {
    $schedule = Schedule::findOrFail($id);
    // Saare stops order wise mangwao
    $stoppages = $schedule->stoppages()->with('location')->get();
    
    return response()->json([
        'schedule' => $schedule,
        'stoppages' => $stoppages
    ]);
}

// 3. Update Logic (Edit Save karne ke liye)
public function update(Request $request, $id) {
    DB::beginTransaction();
    try {
        $schedule = Schedule::findOrFail($id);
        
        // Main schedule update (Date/Days)
        $schedule->update([
            'vehicle_id' => $request->vehicle_id,
            'specific_date' => $request->specific_date,
            'days_of_week' => $request->specific_date ? null : (isset($request->days) ? implode(',', $request->days) : 'Daily'),
        ]);

        // 🔥 Important: Purane stoppages delete karke naye wale save karo
        $schedule->stoppages()->delete();

        $locations = $request->stop_location;
        $arrivals = $request->stop_arrival;
        $departures = $request->stop_departure;

        foreach ($locations as $index => $locId) {
            \App\Models\ScheduleStoppage::create([
                'schedule_id' => $schedule->id,
                'location_id' => $locId,
                'arrival_time' => $arrivals[$index],
                'departure_time' => $departures[$index],
                'stop_order' => $index,
            ]);
        }

        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'Schedule Updated Successfully!']);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

// 4. Delete Logic
public function destroy($id) {
    $schedule = Schedule::findOrFail($id);
    // Stoppages apne aap delete ho jayenge agar migration mein 'cascade' lagaya hai
    $schedule->delete(); 
    return response()->json(['status' => 'success', 'message' => 'Schedule Deleted!']);
}


public function store(Request $request)
{
    DB::beginTransaction();
    try {
        $schedule = Schedule::create([
            'vehicle_id' => $request->vehicle_id,
            'specific_date' => $request->specific_date,
            'days_of_week' => $request->specific_date ? null : (isset($request->days) ? implode(',', $request->days) : 'Daily'),
        ]);

        $locations = $request->stop_location;
        $arrivals = $request->stop_arrival;
        $departures = $request->stop_departure;

        foreach ($locations as $index => $locId) {
            \App\Models\ScheduleStoppage::create([
                'schedule_id' => $schedule->id,
                'location_id' => $locId,
                'arrival_time' => $arrivals[$index],
                'departure_time' => $departures[$index],
                'stop_order' => $index, // 0, 1, 2... automatically sorted
            ]);
        }

        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'Schedule and Route Path saved successfully!']);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
}