<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Car extends Model
{
    use HasFactory ,  SoftDeletes, LogsActivity;

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function carType()
    {
        return $this->belongsTo(Car_type::class, 'car_type');
    }
    public function carModel()
    {
        return $this->belongsTo(Car_model::class, 'car_model');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'mark');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'plate_city_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function getActivitylogOptions():LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['driver_id'])
            /*->logOnlyDirty()*/;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        if(Auth::guard('admin')->check())
            $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
        else
            $activity->causer_id=0;
    }
}
