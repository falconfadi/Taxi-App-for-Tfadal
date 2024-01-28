<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'schedule_trip_discount','schedule_trip_value'    ];
}
