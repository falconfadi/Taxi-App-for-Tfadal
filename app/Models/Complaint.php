<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Complaint extends Model
{
    use HasFactory/*,  LogsActivity*/;

    protected $fillable = [
        'is_open'
    ];
    public function feedback_reason()
    {
        return $this->belongsTo(Feedbak_reason::class, 'status');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function getActivitylogOptions():LogOptions
    {
//        return LogOptions::defaults()
//            ->logOnly(['brand'])
            /*->logOnlyDirty()*/;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        //$activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
    }
}
