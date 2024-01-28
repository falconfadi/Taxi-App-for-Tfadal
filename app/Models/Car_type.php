<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Car_type extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'car_type';

    public function cars()
    {
        return $this->hasMany(Car::class,'car_type');
    }

    public function getExistCarTypes($not_furniture){
        if($not_furniture==1){
            $carTypes = Car_type::join('cars','cars.car_type','=','car_type.id')
                ->select('name','car_type.id as id','name_ar','price','car_type.image as image')
           /* ->where('car_type.id','!=',10)*/
            ->orderBy('id')
                ->get();
            $carTypes = $carTypes->unique('id');
            return $carTypes;
            //futniture type

        }else{
            $carTypes = Car_type::join('cars','cars.car_type','=','car_type.id')
                ->select('name','car_type.id as id','name_ar','price','car_type.image as image')
                ->where('car_type.id',10)
                ->orderBy('id')
                ->get();
            $carTypes = $carTypes->unique('id');
            return $carTypes;
        }
    }

    public function getExistCarTypesWithoutFurniture(){
        //all types except furniture
        $carTypes = Car_type::RightJoin('cars','cars.car_type','=','car_type.id')
            ->select('name','car_type.id as id','name_ar','price','car_type.image as image')
            ->where('car_type.id','!=',10)
            ->orderBy('id')
            ->get();
        $carTypes = $carTypes->unique('id');
        return $carTypes;
    }

    public function getActivitylogOptions():LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name','price'])
            /*->logOnlyDirty()*/;

    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
    }



}
