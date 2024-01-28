<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Admin\KpiDriversController;
use App\Http\Controllers\Admin\KpiTripController;
use App\Http\Controllers\Admin\KpiUsersController;
use App\Http\Controllers\Admin\SumMoneyController;
use App\Http\Controllers\Api\CarController;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Cancel_reason_text;
use App\Models\Car;
use App\Models\Car_type;
//use App\Models\Complaint;
//use App\Models\Counter;
use App\Models\Coordinate;
use App\Models\Counter;

use App\Models\DriverCompensation;
use App\Models\Trip;


class TestController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Damascus');
    }



    public function test()
    {
        //save invoice

        $tripId = 650;
        $trip = Trip::find($tripId);
        echo $trip->id;            echo "-";
        $carObj = new CarController();
        $counter = new Counter();
        $record = $counter->getLatestCounter($tripId, $trip->driver_id);
        echo $record->id;
        $latitude = 33.4235784;
        $longitude = 36.070614;

        //$counter = new Counter();
        $tripObj = new TripController();
        if($record) {
            $distance = floatval($carObj->directDistance($record->latitude, $record->longitude, $latitude, $longitude, 1.1));
            echo "-";
            echo $distance;
            if ($distance > 0.005 && !is_null($trip->end_date)) {
                //$counter = new Counter();
                $counter->latitude = $latitude;
                $counter->longitude = $longitude;
                $counter->distance = $distance;
                $counter->counter = $record->counter + 1;
                $counter->user_id = $trip->driver_id;
                $counter->price = round($tripObj->PricePerKilometer($distance, $trip->carType->price), 2);
                //$whole_price = (float)$counter->price + (float)$record->whole_price;
                $counter->whole_price = round((float)$counter->price + (float)$record->whole_price, 2);
                $counter->whole_distance = $distance + (float)$record->whole_distance;
                $counter->trip_id = $tripId;
                $counter->save();
                $counter->whole_distance = round($distance + (float)$record->whole_distance, 3);
            }
        }

    }

    public function test1()
    {
        $trip = Trip::find(647);
        $Coo = new  DriverCompensation();
        $x = $Coo->reCalculateCompensation($trip);
        //echo $x;
    }






}
