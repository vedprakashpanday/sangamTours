<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model {
    use SoftDeletes;
    protected $guarded = [];

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }

    public function amenities() {
        return $this->belongsToMany(Amenity::class);
    }

    // 🔥 NEW: Image Relationship (Polymorphic)
    public function images()
    {
        return $this->morphMany(CommonImage::class, 'imageable');
    }
}