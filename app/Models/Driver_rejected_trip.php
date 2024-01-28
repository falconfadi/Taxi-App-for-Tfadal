<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Driver_rejected_trip extends Model
{
    use HasFactory;

//    public function trip()
//    {
//        return $this->belongsTo(Trip::class, 'trip_id');
//    }

//    use LogsActivity;
//
//    public function getActivitylogOptions():LogOptions
//    {
//        return LogOptions::defaults()
//            ->logOnly(['brand'])
//            /*->logOnlyDirty()*/;
//    }
//
//    public function tapActivity(Activity $activity, string $eventName)
//    {
//        $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
//    }
}
