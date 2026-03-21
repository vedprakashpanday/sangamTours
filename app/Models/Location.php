<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['country_name', 'state_name', 'city_location'];

    /**
     * Get all packages for this location.
     */
    public function packages()
    {
        return $this->hasMany(TourPackage::class);
    }
}