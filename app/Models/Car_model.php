<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


//use App\Models\Make;

class Car_model extends Model
{
    use HasFactory;
    use LogsActivity;
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function getActivitylogOptions():LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['model','brand.brand_id'])
            /*->logOnlyDirty()*/;
    }
    public function tapActivity(Activity $activity, string $eventName)
    {
       // $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
        if(Auth::guard('admin')->check())
            $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
        else
            $activity->causer_id = 0;
    }
}
