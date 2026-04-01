<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model {
    use SoftDeletes;
    protected $guarded = [];

    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }

   public function vendorType() 
{
    return $this->belongsTo(VendorType::class, 'vendor_type_id');
}
}