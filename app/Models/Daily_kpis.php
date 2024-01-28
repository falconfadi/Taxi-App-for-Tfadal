<?php

namespace App\Models;

use App\Http\Controllers\Api\CarController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily_kpis extends Model
{
    use HasFactory;


    public function updateDailyKPI($driver_id,$trip_id){

        //$trip = Trip::find($trip_id);
        //$carObj = new CarController();
        //$distance = $carObj->googleMapDistance($trip->latitude_from, $trip->longitude_from, $trip->latitude_to, $trip->longitude_to);
        $c = new Counter();
        $counter = $c->getLatestCounter($trip_id, $driver_id);
        $price = 0;
        $invoice = Invoice::where('trip_id',$trip_id)->first();

        $price =($invoice)? $invoice->price:0 ;

        $x = $this::where('driver_id',$driver_id)->whereDate('updated_at',Carbon::today())->first();
        if($x){
            $x->completed_trips += 1;
            $x->whole_distance += ($counter)?$counter->whole_distance:0;
            $x->money += $price;
            $x->update();
        }
        else{
            //create new one
            $this->completed_trips =  1;
            $this->whole_distance =  ($counter)?$counter->whole_distance:0;
            $this->driver_id =  $driver_id;
            $this->money = $price;
            $this->save();
        }
    }
}
