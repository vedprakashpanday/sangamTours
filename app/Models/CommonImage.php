<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonImage extends Model
{
    protected $fillable = ['filename', 'image_type', 'imageable_id', 'imageable_type'];

    /**
     * Get the parent imageable model (TourPackage, etc.).
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}