<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accommodation extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function images() {
        return $this->morphMany(CommonImage::class, 'imageable');
    }

    public function accommodationType()
{
    return $this->belongsTo(AccommodationType::class, 'accommodation_type_id');
}

protected $fillable = [
    'accommodation_id', 'name', 'accommodation_type_id', 
    'star_rating', 'price_per_night', 'description', 
    'location_id', 'status'
];


public function amenities()
{
    return $this->belongsToMany(Amenity::class, 'accommodation_amenity');
}
}