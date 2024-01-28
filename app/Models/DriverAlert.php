<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DriverAlert extends Model
{
    use HasFactory, LogsActivity;
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }



    public function getActivitylogOptions():LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['text'])
            /*->logOnlyDirty()*/;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
    }
}
