<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model {
    use SoftDeletes;
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function route() { return $this->belongsTo(Route::class); }
    public function package() { return $this->belongsTo(TourPackage::class); }
}
