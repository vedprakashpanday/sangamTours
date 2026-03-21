<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use App\Models\Accommodation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Real Data from existing tables
        $totalPackages = TourPackage::count();
        $totalHotels = Accommodation::count();

        // Dummy Data (Inhe hum Customer/Booking table banane ke baad dynamic karenge)
        $data = [
            'total_packages' => $totalPackages,
            'total_hotels' => $totalHotels,
            'total_bookings' => 0, 
            'total_revenue' => 0,
            'customer_stats' => [
                'active' => 0,
                'blocked' => 0,
                'restricted' => 0
            ]
        ];

        return view('admin.dashboard', compact('data'));
    }
}