<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = []; // Ya aapne jo fillable likha tha wahi rehne dein

    // 🔥 Ye relationship missing thi, ise add karein
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    // Naya stoppage wala relationship
    public function stoppages()
    {
        return $this->hasMany(ScheduleStoppage::class, 'schedule_id')->orderBy('stop_order', 'asc');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}