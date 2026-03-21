<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 🔥 Ye line add karein

class Route extends Model
{
    use SoftDeletes; // 🔥 Ye line add karein

    protected $guarded = [];

    // Relationship with Locations
    public function fromCity() {
        return $this->belongsTo(Location::class, 'from_city_id');
    }

    public function toCity() {
        return $this->belongsTo(Location::class, 'to_city_id');
    }
}