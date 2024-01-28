<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $table = 'admins';
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'admin_id');
    }

    public function employees ()
    {
        return $this->belongsToMany(User::class,'companies_users','admin_id','user_id' );
    }
    //trips of a company
    public function trips ()
    {
        return $this->belongsToMany(Trip::class,'companies_trips','admin_id','trip_id' );
    }

    public function getActivitylogOptions():LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name','email','phone'])
            /*->logOnlyDirty()*/;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
    }
//    public function roles() {
//        return $this->belongsToMany(Role::class);
//    }
}
