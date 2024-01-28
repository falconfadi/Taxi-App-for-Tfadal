<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Trip;
use App\Models\TripDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function updateCounter(Request $request){

        $carObj = new CarController();
        $counter = new Counter();

        $trip = Trip::find($request->trip_id);
        if($trip->status==3){
            $record = $counter->getLatestCounter($request->trip_id, $request->driver_id);
            //$counter = new Counter();
            $tripObj = new TripController();
            if($record){
                $distance =  floatval($carObj->directDistance($record->latitude, $record->longitude, $request->latitude, $request->longitude, 1.3));
                $validateCoordinate = $this->validateCoordinates($distance,$record->created_at);
                if($distance>0.006 && $counter->latitude!=$request->latitude ){
                    $counter->latitude = $request->latitude;
                    $counter->longitude = $request->longitude;
                    $counter->distance = $distance;
                    $counter->counter = $record->counter+1;
                    $counter->user_id = $request->driver_id;
                    $counter->price =  round($tripObj->PricePerKilometer($distance, $trip->carType->price),2) ;
                    //$whole_price = (float)$counter->price + (float)$record->whole_price;
                    $counter->whole_price = round((float)$counter->price + (float)$record->whole_price,2);
                    $counter->whole_distance = $distance + (float)$record->whole_distance;
                    $counter->trip_id = $request->trip_id;
                    $counter->save();
                    $counter->whole_distance = round($distance + (float)$record->whole_distance,3);
                }else{
                    $counter = $record;
                }
            }
            else{
                //first count
                $distance = 0;
                $counter->latitude = $request->latitude;
                $counter->longitude = $request->longitude;
                $counter->distance = $distance;
                $counter->counter = 0;
                $counter->user_id = $request->driver_id;
                //$tripObj = new TripController();
                $counter->price =  $tripObj->preEstimatePrice($distance , $trip->carType->price) ;
                $counter->whole_price = $counter->price ;
                $counter->whole_distance = $distance ;
                $counter->trip_id = $request->trip_id;
                $counter->save();
                $counter->whole_distance = round($distance,3) ;
            }
        }else{
            //trip did not started
            $distance = 0;
            $counter->latitude = $request->latitude;
            $counter->longitude = $request->longitude;
            $counter->distance = $distance;
            $counter->counter = 0;
            $counter->user_id = $request->driver_id;
            $counter->price =  0 ;
            $counter->whole_price = 0;
            $counter->whole_distance = $distance ;
            $counter->trip_id = $request->trip_id;
        }

        return $counter;
    }

    public function lastCounter(Request $request){

        //$carObj = new CarController();
        $counter = new Counter();
        //$record = Counter::where('trip_id',$request->trip_id)->orderBy('id','desc')->limit(1)->first();
        $trip = Trip::find($request->trip_id);
        if($trip){
            $record = $counter->getLatestCounter($request->trip_id, $trip->driver_id);
            if($record){
                $record->whole_distance = round($record->whole_distance,3);
                return $record;
            }
            else{
                $counter = new Counter();
                $counter->latitude = '';
                $counter->longitude ='';
                $counter->distance = '';
                $counter->counter = '';
                $counter->user_id = '';
                $counter->price =  '' ;
                $counter->whole_price = '';
                $counter->whole_distance = '' ;
                $counter->trip_id = '';
                return $counter;
            }
        }else{
            $counter = new Counter();
            $counter->latitude = '';
            $counter->longitude ='';
            $counter->distance = '';
            $counter->counter = '';
            $counter->user_id = '';
            $counter->price =  '' ;
            $counter->whole_price = '';
            $counter->whole_distance = '' ;
            $counter->trip_id = '';
            return $counter;
        }
    }

    public function getDifferenceOfTimeInSeconds($start_time, $end_time)
    {
        $seconds = abs(strtotime($end_time) - strtotime($start_time)) ;
        return $seconds;
    }

    public function validateCoordinates($distance, $lastRecordTime){
        // time 60 sec
        // distance between 0.02 and 2 km
        $now = Carbon::now();
        //echo $now;echo "<br>";
        $timeInSeconds = $this->getDifferenceOfTimeInSeconds($now, $lastRecordTime);
        //echo $timeInSeconds;echo "<br>";
        $max = 0.0333; $min = 0.000333;
        if($timeInSeconds != 0){
            $ratio = $distance/$timeInSeconds;
            //echo $ratio;echo "<br>";
            if($ratio >= $min && $ratio <= $max){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
