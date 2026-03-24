<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourPackage;

class HomeController extends Controller
{
    public function index()
    {
        // Abhi ke liye sirf view load karte hain
        // Baad mein hum yahan se $featuredPackages bhejenge
        return view('user.home');
    }

    public function allPackages()
    {
        return "Yahan saare Tour Packages dikhenge";
    }

    public function packageDetail($slug)
    {
        return "Package detail page for: " . $slug;
    }
}