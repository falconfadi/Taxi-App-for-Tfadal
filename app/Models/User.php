<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;
    use LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'phone',
        'fcm_token',
        'latitude',
        'longitude',
        'is_driver',
        'image',
        'device_token',
        'address',
        'ip',
        'gender',
        'operation_system_version',
        'system_type',
        'freeze',
        'career_id'
    ];

    public function drivers_details()
    {
        return $this->hasOne(Driver::class, 'user_id');
    }
    public function car_()
    {
        return $this->hasOne(Car::class, 'driver_id');
    }
    public function tripsDriver()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $url = url('reset-password?token=' . $token);
        //$url = 'https://spa.test/reset-password?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }

    public function getDrivers(){
        $drivers = User::where('is_driver',1)->whereNotNull('fcm_token')/*->whereHas('drivers_details',function($subQ) {
            $subQ->where('freeze',0);
        })*/->with('drivers_details')->get();

        return $drivers;
    }
    public function getAllDrivers(){
        $drivers = User::where('is_driver',1)/*->whereNotNull('fcm_token')*/
        ->with('drivers_details')->get();
        return $drivers;
    }
    public function getAllUsers(){
        //$users = User::where('is_driver',0)->whereNotNull('fcm_token')->get();
        $users = User::where('is_driver',0)->get();
        return $users;
    }
    public function getUsersIds(){
        //$users = User::where('is_driver',0)->whereNotNull('fcm_token')->get();
        $users = User::where('is_driver',0)->whereNotNull('fcm_token')->pluck('id')->toArray();
        return $users;
    }
    public function getDriversIds(){
        //$users = User::where('is_driver',0)->whereNotNull('fcm_token')->get();
        $users = User::where('is_driver',1)->whereNotNull('fcm_token')->pluck('id')->toArray();
        return $users;
    }

    public function getDriversByCarType($car_type_id){
        $drivers = User::where('is_driver',1)->whereNotNull('fcm_token')
            ->whereHas('drivers_details',function($subQ) {
            $subQ/*->where('freeze',0)*/->where('is_connected',1)->where('verified',1);
        })
            ->whereHas('car_',function($subQ) use($car_type_id) {
                $subQ->where('car_type',$car_type_id);
            })
            ->with('drivers_details')->get();
        return $drivers;
    }

    public function getDriverswithActiveTrip(){
        $drivers = User::where('is_driver',1)->whereNotNull('fcm_token')
            ->whereHas('tripsDriver',function($subQ) {
                $subQ->whereIn('status',[1,2,3])
            ->whereDate('start_date','<=',Carbon::today());
            })
            ->with('drivers_details','tripsDriver')->get();

        return $drivers;
    }

    //get drivers without trip
    public function getAvailableDrivers(){
        //$activeTripsDriversIds = Trip::with('user')->whereIn('status',[1,2,3])->pluck('driver_id')->toArray();
        $drivers = User::where('is_driver',1)->whereNotNull('fcm_token')->whereHas('drivers_details',function($subQ) {
                $subQ->where('is_connected',1);
            })
        ->whereNotIn('id',$this->getDriversIdswithActiveTrip())
        ->get();
        return $drivers;
    }

    public function getDriversIdswithActiveTrip(){
        $drivers = User::where('is_driver',1)->whereNotNull('fcm_token')->with('tripsDriver')/*whereHas('drivers_details',function($subQ) {
            $subQ->where('freeze',0);
        })*/
        ->whereHas('tripsDriver',function($subQ) {
            $subQ->whereIn('status',[1,2,3])
            ->whereDate('start_date','<=',Carbon::today());
        })
        ->pluck('id')->toArray();
        return $drivers;
    }

    public function getUserIdswithActiveTrip(){
        $users = User::where('is_driver',0)->whereNotNull('fcm_token')/*whereHas('drivers_details',function($subQ) {
            $subQ->where('freeze',0);
        })*/
        ->whereHas('tripsDriver',function($subQ) {
            $subQ->whereIn('status',[1,2,3]);
        })
            ->pluck('id')->toArray();

        return $users;
    }



    public function offers()
    {
        return $this->belongsToMany(Offers::class,'offers_users','user_id','offer_id' );
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class,'notifications_users','user_id','notification_id' );
    }

    //morph...add note
    public function note()
    {
        return $this->morphOne(Note::class, 'notetable');
    }

    public function sum_moneys() {
        return $this->hasMany(Sum_money::class,'driver_id');
    }
    //get latest record by driver id
    public function balance() {
        return $this->hasOne(Sum_money::class,'driver_id')->where('driver_id',$this->id)->orderByDesc('id');
        //return $this->sum_moneys()->max('sum_moneys.id');
    }


    public function verificationCode()
    {
        return $this->hasOne(VerificationCode::class, 'user_id');
    }

    // get connected and verified drivers
    public function getConnectedDrivers(){
        $drivers = User::where('is_driver',1)->whereHas('drivers_details',function($subQ) {
            $subQ->where('verified',1);
            $subQ->where('is_connected',1);
        })->get();

        return $drivers;
    }

    public function getActivitylogOptions():LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['details'])
            /*->logOnlyDirty()*/;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        //Auth::guard('admin')->check()
        if(Auth::guard('admin')->check())
        $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
        else
            $activity->causer_id=0;
    }


}
