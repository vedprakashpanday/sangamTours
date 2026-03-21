<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            ['name' => 'WiFi', 'icon' => 'bx bx-wifi'],
            ['name' => 'Air Conditioned (AC)', 'icon' => 'bx bx-wind'],
            ['name' => 'Complimentary Meal', 'icon' => 'bx bx-restaurant'],
            ['name' => 'Water Bottle', 'icon' => 'bx bx-water'],
            ['name' => 'Charging Point', 'icon' => 'bx bx-battery-charging'],
            ['name' => 'Blankets & Pillow', 'icon' => 'bx bx-bed'],
            ['name' => 'Recliner Seats', 'icon' => 'bx bx-chair'],
            ['name' => 'GPS Tracking', 'icon' => 'bx bx-map-pin'],
            ['name' => 'Entertainment (TV)', 'icon' => 'bx bx-tv'],
            ['name' => 'Emergency Exit', 'icon' => 'bx bx-exit'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}