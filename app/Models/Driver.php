<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Driver extends Model
{
    use HasFactory, HasApiTokens , SoftDeletes/*, LogsActivity*/;

    protected $fillable = [
        'freeze', 'verified', 'birthdate', 'email','full_name','not_convicted_image','national_id','phone'
    ];

    public function driver_as_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function region(){
        return $this->belongsTo(Region::class, 'region_id');
    }




//    public function getActivitylogOptions():LogOptions
//    {
//        return LogOptions::defaults()
//            ->logOnly(['user_id'])
//            /*->logOnlyDirty()*/;
//    }
//
//    public function tapActivity(Activity $activity, string $eventName)
//    {
//        if(Auth::guard('admin')->check())
//            $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
//        else
//            $activity->causer_id=0;
//    }


    public function isVerified($user_id){
        $dr = self::where('user_id',$user_id)->first()->verified;
        if($dr==1){
            return true;
        }
        return false;

    }

}
