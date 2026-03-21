<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourPackage extends Model
{
    use SoftDeletes;
    protected $fillable = ['package_id', 'title', 'price', 'discount_price', 'details', 'location_id', 'status'];

    public function images()
    {
        return $this->morphMany(CommonImage::class, 'imageable');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Itinerary/Stays ke liye relationship
    public function stays()
    {
        return $this->hasMany(PackageStay::class);
    }
}