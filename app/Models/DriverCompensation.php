<?php

namespace App\Models;

use App\Http\Controllers\Api\CarController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverCompensation extends Model
{
    use HasFactory;

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function addItem( $trip, $compensationPerKilo){
        $C = new CarController();
        $Coo = new Coordinate();
        $driverCoordinates = $Coo->driverCoordinatesWhenApproveTrip($trip,$trip->driver_id );
        //if not cancelled trip
        if($trip->status != 5){
           if($driverCoordinates){
               if($trip->is_scheduled==0){
                   $distance = $this->distanceForCompensation($driverCoordinates->latitude, $driverCoordinates->longitude, $trip->latitude_from, $trip->longitude_from);
                   $driverCompensation = $compensationPerKilo * $distance;
                   $this->latitude = $driverCoordinates->latitude;
                   $this->longitude =$driverCoordinates->longitude;
                   $this->distance = $distance;//$trip_id->driver_id;
                   $this->trip_id = $trip->id;
                   $amount = $C->roundUp($driverCompensation);
                   $this->amount = $amount;
                   $this->save();
                   //add compensation value to driver balance
                   $b = new Balance();
                   $sumMoney = new Sum_money();
                   $b->addBalanceRecord($trip->driver_id, $amount, '',4,false, $trip->id);
                   $sumMoney->updateMoney($trip->driver_id, $amount);
               }else{
                   $distance_compensation_scheduled = TripSetting::find(1)->distance_compensation_scheduled;
                   $this->latitude = $driverCoordinates->latitude;
                   $this->longitude =$driverCoordinates->longitude;
                   $this->distance = 0;//$trip_id->driver_id;
                   $this->trip_id = $trip->id;
                   $amount = $distance_compensation_scheduled;
                   $this->amount = $amount;
                   $this->save();
                   //add compensation value to driver balance
                   $b = new Balance();
                   $sumMoney = new Sum_money();
                   $b->addBalanceRecord($trip->driver_id, $amount, '',4,false, $trip->id);
                   $sumMoney->updateMoney($trip->driver_id, $amount);
               }
            }
        }else{

        }

    }
    public function addItem1($driver_lat, $driver_lng, $trip,$compensationPerKilo){
        $C = new CarController();
        $distance_compensation_scheduled = TripSetting::find(1)->distance_compensation_scheduled;
        //$Coo = new Coordinate();
        //$driverCoordinates = $Coo->driverCoordinatesWhenApproveTrip($trip,$trip->driver_id );
        //if not cancelled trip
        if($trip->status != 5){
            if ($trip->is_scheduled==0)
            {
                $distance = $this->distanceForCompensation($driver_lat, $driver_lng,  $trip->latitude_from,  $trip->longitude_from);
                $driverCompensation = $compensationPerKilo * $distance;
                $this->latitude = $driver_lat;
                $this->longitude = $driver_lng;
                $this->distance = $distance;
                $this->trip_id = $trip->id;
                $amount = $C->roundUp($driverCompensation);
                $this->amount = $amount;
                //add compensation value to driver balance
                $b = new Balance();
                $sumMoney = new Sum_money();
                $b->addBalanceRecord($trip->driver_id, $amount, '',4,false, $trip->id);
                $sumMoney->updateMoney($trip->driver_id, $amount);

            }else{
                //$driverCompensation = 6000;
                $this->latitude = $driver_lat;
                $this->longitude = $driver_lng;
                $this->distance = 0;//$trip_id->driver_id;
                $this->trip_id = $trip->id;
                $amount = $distance_compensation_scheduled;
                $this->amount = $amount;
                $this->save();
                //add compensation value to driver balance
                $b = new Balance();
                $sumMoney = new Sum_money();
                $b->addBalanceRecord($trip->driver_id, $amount, '',4,false, $trip->id);
                $sumMoney->updateMoney($trip->driver_id, $amount);
            }
        }

    }

    public function distanceForCompensation($driver_lat, $driver_lng, $latitude_from, $longitude_from){
        $C = new CarController();
        $distance =  $C->openStreetMapDistance($driver_lat, $driver_lng, $latitude_from, $longitude_from);
        return $distance;
    }

    public function getlastNItem($n)
    {
        return $this->orderBy('id','desc')->take($n)->get();
    }

    public function reCalculateCompensation($trip){
        if($trip->driver_id != 0){
            $compensation = DriverCompensation::where('trip_id',$trip->id)->first();
            if($compensation){
                $balance = Balance::where('trip_id',$trip->id)->where('is_renew',4)->first();
                if($balance){
                    $balance->delete();
                    $sumMoney = new Sum_money();
                    $amount = $compensation->amount;
                    $sumMoney->updateMoney($trip->driver_id,(-1)*$amount);
                }
                $compensation->delete();
            }
        }
    }

}
