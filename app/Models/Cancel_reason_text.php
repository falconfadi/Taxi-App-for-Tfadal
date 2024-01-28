<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Cancel_reason_text extends Model
{
//    public $user_id;
//    public $trip_id;
//    public $reason_id;
//    public $reason_text;
//    public $is_admin;

    protected $table = 'cancel_reason_text';
    use HasFactory;
    use LogsActivity;

    public function __construct()
    {

    }

    public function cancelReason()
    {
        return $this->belongsTo(Cancel_reason::class, 'reason_id');
    }
//    public function __construct($user_id,$trip_id, $reason_id,  $reason_text, $is_admin)
//    {
//        parent::__construct();
//        $this->user_id = $user_id;
//        $this->trip_id = $trip_id;
//        $this->reason_id = $reason_id;
//        $this->reason_text = $reason_text;
//        $this->is_admin = $is_admin;
//    }

    public function getActivitylogOptions():LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id'])
            /*->logOnlyDirty()*/;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
    }
}
