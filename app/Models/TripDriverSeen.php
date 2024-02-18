<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripDriverSeen extends Model
{
    use HasFactory;
    protected $table = 'trips_drivers_seen';
    protected $fillable = [
        'trip_id',
        'driver_id'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }
    public function TripNotSeen($driverId, $carTypeId){


        $trip = //$this->where('driver_id',$driverId)->wherehas('trip', function($q)use($carTypeId) {
                Trip::where('status',0)
                    ->where('is_scheduled',0)
                    ->where('car_type_id',$carTypeId)
                    ->orderBy('id','DESC')
                    ->whereNotIn('id', $this->where('driver_id',$driverId)->pluck('trip_id')->toarray())
        //})
            ->first();
        return $trip;
    }
}
