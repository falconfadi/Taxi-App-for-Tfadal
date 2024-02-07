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
}
