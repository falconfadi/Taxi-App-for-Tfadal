<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coordinate extends Model
{
    use HasFactory;

    protected $fillable = [
        'longitude',
        'latitude',
        'driver_id'

    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // driver coordinate during specific trip
    public function driverCoordinatesDuringTrip($trip, $driver_id){
        if($trip->start_date){
            $tripDate = Carbon::createFromFormat('Y-m-d H:i:s', $trip->start_date);

            $results = Coordinate::where('driver_id',$driver_id)
                ->whereDate('created_at','>=', $tripDate)
                ->where('trip_id', $trip->id)
                ->orderBy('created_at')
                ->get();
            return $results;
        }else{
            return false;
        }

    }
    //get connected drivers
    public function driversLastLocation(){
        //$tripDate = Carbon::createFromFormat('Y-m-d H:i:s', $trip->trip_date);
        //echo $driver_id;
        $results = DB::table('coordinates')
            ->select(DB::raw('MAX(coordinates.id) as id ,coordinates.latitude as latitude,coordinates.longitude as longitude,driver_id,name,last_name,father_name,drivers.id as driver_details_id'))
            ->join('users','coordinates.driver_id','=','users.id')
            ->join('drivers','drivers.user_id','=','users.id')
            ->where('is_connected',1)
            ->groupBy('driver_id')
        ->get();

        return $results;
    }

    public function driversLastLocationByIds($driversIds){
        //$tripDate = Carbon::createFromFormat('Y-m-d H:i:s', $trip->trip_date);
        //echo $driver_id;
        $results = DB::table('coordinates')
            ->select(DB::raw('MAX(coordinates.id) as id ,coordinates.latitude as latitude,coordinates.longitude as longitude,driver_id,name,last_name,father_name,drivers.id as driver_details_id'))
            ->join('users','coordinates.driver_id','=','users.id')
            ->join('drivers','drivers.user_id','=','users.id')
            ->where('is_connected',1)
            ->whereIn('driver_id',$driversIds)
            ->groupBy('driver_id')
            ->get();

        return $results;
    }
    public function lastDriverCoordinatesByTrip($trip){
        //$tripDate = Carbon::createFromFormat('Y-m-d H:i:s', $trip->trip_date);
        //echo $driver_id;
        $results = DB::table('coordinates')

            ->where('driver_id',$trip->driver_id)
            ->where('trip_id',$trip->id)
            ->orderBy('id','DESC')
            ->first();

        return $results;
    }



    public function update_location( $driver_id, $latitude, $longitude, $trip_id=0)
    {
        //echo $latitude;
        if($latitude > 0 && $longitude > 0 && $driver_id > 0)
        {
            //echo $driver_id;
            $user = User::find($driver_id);
            //var_dump($user);exit();
            if($user){
                $user->latitude = $latitude;
                $user->longitude = $longitude;
                $user->save();
                //add location to the coordinates
                $this->latitude = $latitude;
                $this->longitude = $longitude;
                $this->driver_id = $driver_id;
                $this->trip_id = $trip_id;
                //$this->save();
                if ($this->save()) {
                    return true;
                }
                else {
                    return  false;
                }
            }
        }else{
            return  false;
        }
    }

    public function driverCoordinatesWhenApproveTrip($trip, $driver_id){

            //$date = Carbon::createFromFormat('Y-m-d H:i:s', $trip->driver_approve_time);
            $result = Coordinate::where('driver_id',$driver_id)
                /*->whereDate('created_at','>=', $date)*/
                ->where('trip_id', $trip->id)
                ->orderBy('created_at')
                ->first();
            return $result;
    }
}
