<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccommodationType;
use App\Models\Accommodation;
use App\Models\Offer; 
use App\Models\TourPackage;
use App\Models\Vehicle; 
use App\Models\VendorType;

class HomeController extends Controller
{
   public function index()
    {
        $accommodationTypes = AccommodationType::where('status', 1)->get();
        $accommodations = Accommodation::with(['images', 'location'])->where('status', 1)->latest()->get();
        $offers = Offer::where('status', 1)->where('valid_until', '>=', date('Y-m-d'))->latest()->get();
        $tourPackages = TourPackage::with(['images', 'location', 'stays'])->where('status', 1)->latest()->take(6)->get();

        // 🔥 DYNAMIC CATEGORY FETCHING FROM VENDOR_TYPES TABLE
        
        // 1. Cabs ke types fetch karein
        $cabTypes = VendorType::where('name', 'LIKE', '%Cab%')
                              ->orWhere('name', 'LIKE', '%Car%')
                              ->orWhere('name', 'LIKE', '%Taxi%')
                              ->pluck('name')->toArray();
        if(empty($cabTypes)) $cabTypes = ['Cab', 'Car', 'Taxi']; // Fallback

        // 2. Auto ke types fetch karein
        $autoTypes = VendorType::where('name', 'LIKE', '%Auto%')
                               ->pluck('name')->toArray();
        if(empty($autoTypes)) $autoTypes = ['Auto', 'Auto Rickshaw'];

        // 3. E-Rickshaw ke types fetch karein
        $eRickshawTypes = VendorType::where('name', 'LIKE', '%E-Rickshaw%')
                                    ->orWhere('name', 'LIKE', '%Toto%')
                                    ->pluck('name')->toArray();
        if(empty($eRickshawTypes)) $eRickshawTypes = ['E-Rickshaw', 'Toto'];

        // 4. Bikes ke types fetch karein
        $bikeTypes = VendorType::where('name', 'LIKE', '%Bike%')
                               ->orWhere('name', 'LIKE', '%Scooty%')
                               ->pluck('name')->toArray();
        if(empty($bikeTypes)) $bikeTypes = ['Bike', 'Scooty', 'Motorcycle'];


        // Saare types ko ek array mein mila lein single query ke liye
        $allWantedTypes = array_merge($cabTypes, $autoTypes, $eRickshawTypes, $bikeTypes);

        // 🔥 SINGLE DATABASE QUERY (Super Fast)
        $allRides = Vehicle::with(['images', 'amenities', 'vendor'])
                           ->where('status', 1)
                           ->whereIn('type', $allWantedTypes)
                           ->latest()
                           ->get();

        // Data ko variables mein baant lein
        $cabs       = $allRides->whereIn('type', $cabTypes)->take(8);
        $autos      = $allRides->whereIn('type', $autoTypes)->take(8);
        $eRickshaws = $allRides->whereIn('type', $eRickshawTypes)->take(8);
        $bikes      = $allRides->whereIn('type', $bikeTypes)->take(8);

        return view('user.home', compact(
            'accommodationTypes', 
            'accommodations', 
            'offers', 
            'tourPackages', 
            'cabs', 
            'autos', 
            'eRickshaws', 
            'bikes'
        )); 
    }
    
    
    
    public function allPackages()
    {
        return "Yahan saare Tour Packages dikhenge";
    }

    public function packageDetail($slug)
    {
        return "Package detail page for: " . $slug;
    }

    public function about()
    {
        return view('user.about'); 
    }
}