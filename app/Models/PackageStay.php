<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageStay extends Model
{
    protected $fillable = ['tour_package_id', 'days', 'nights', 'place_description'];

    public function package()
    {
        return $this->belongsTo(TourPackage::class, 'tour_package_id');
    }
}