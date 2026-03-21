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
}