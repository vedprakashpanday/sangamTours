<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model {
    use SoftDeletes;
    protected $guarded = [];

   
    public function package() { return $this->belongsTo(TourPackage::class); }

    public function passengers()
{
    // Maan lete hain aapka model 'Passenger' naam se hai 
    // aur usme 'booking_id' foreign key hai
    return $this->hasMany(Passenger::class, 'booking_id');
}

// app/Models/Booking.php

public function vehicle() {
    return $this->belongsTo(Vehicle::class, 'vehicle_id');
}

public function route() {
    return $this->belongsTo(Route::class, 'route_id');
}

public function customer() {
    return $this->belongsTo(Customer::class, 'customer_id');
}

}
