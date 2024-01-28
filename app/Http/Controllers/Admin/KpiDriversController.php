<?php

namespace App\Http\Controllers\Admin;

use App\Charts\BarChart;
use App\Http\Controllers\Controller;
use App\Models\Coordinate;
use App\Models\Kpi_driver;
use App\Models\TimeToArriveDriver;
use Illuminate\Http\Request;

class KpiDriversController extends Controller
{
    public function index()
    {
        $title = __('page.Drivers_KPIs');
        $kpi_users = Kpi_driver::with('drivers')->get();
        return view('admin.drivers_kpi.drivers',compact('kpi_users','title'));

    }

    public function avgArriveTime($user_id ){
        $timesForDriver = TimeToArriveDriver::where('driver_id',$user_id)->get();
        if($timesForDriver)
        {
            $sum = 0;
            foreach ($timesForDriver as $timeForDriver)
            {
                $sum += $timeForDriver->time_arrive;
            }
            $avg = $sum/count($timesForDriver);
            $x = Kpi_driver::where('driver_id',$user_id)->first();
            if($x){
                $x->avg_reach_time = $avg;
                $x->update();
                //return true;
            }
            else{
                $y = new Kpi_driver();
                $y->avg_reach_time = $avg;
                $y->driver_id =  $user_id;
                $y->sum_trip_cancelled = 0;
                $y->save();
               // return true;
            }
        }
    }

    public function sumTripCancelled($user_id ){

        $x = Kpi_driver::where('driver_id',$user_id)->first();
        if($x){
            $x->sum_trip_cancelled += 1;
            $x->update();
            return true;
        }
        else{
            $y = new Kpi_driver();
            $y->sum_trip_cancelled =  1;
            $y->driver_id =  $user_id;
            $y->save();
            return true;
        }
    }

    public function sumTripAcheived($user_id ){

        $x = Kpi_driver::where('driver_id',$user_id)->first();
        if($x){
            $x->sum_trip_acheived += 1;
            $x->update();
            return true;
        }
        else{
            $y = new Kpi_driver();
            $y->sum_trip_acheived =  1;
            $y->driver_id =  $user_id;
            $y->save();
            return true;
        }
    }

    public function sumMoneyAchieved($user_id ,$price){

        $x = Kpi_driver::where('driver_id',$user_id)->first();
        if($x){
            $x->sum_money_acheived = $x->sum_money_acheived + $price;
            $x->update();
            return true;
        }
        else{
            $y = new Kpi_driver();
            $y->sum_money_acheived =  $price;
            $y->driver_id =  $user_id;
            $y->save();
            return true;
        }
    }

    public function x1(){
//        $user_id=42 ;
//        $this->sumTripCancelled($user_id);
//        var locations = [
//            ['Manly Beach', 33.52574386128534, 36.318072848768885, 1],
//            ['Bondi Beach', 33.521884993298244, 36.315623530149544, 2],
//            ['Coogee Beach', 33.519950574981344, 36.31112367642512, 3]
//            /*['Maroubra Beach',33.51784204737573, 36.316653498405536, 4],
//            ['Cronulla Beach', 33.5158528748027, 36.31982864777078, 5]*/
//        ];

//        $permission = Coordinate::create(['latitude' => '33.521884993298244', 'longitude' => '36.315623530149544',
//            'driver_id' => '3']);
////        $permission = Coordinate::create(['latitude' => '33.519950574981344', 'longitude' => '36.31112367642512',
//            'driver_id' => '3']);

        $coordinates = new Coordinate();
        $coordinates->latitude = '454546';
        $coordinates->longitude = '454546';
        $coordinates->driver_id = '1';
        $coordinates->save();
    }


    public function calculateAcheivedMoney($driver_id){
        $x = Kpi_driver::where('driver_id',$driver_id)->first();
        return $x->sum_money_acheived ;
    }

    //chart
    public function best_drivers(BarChart $chart ){
        $title = $chartTitle = 'أفضل عشرة كباتن';
        $y = new Kpi_driver();
        $best_users = $y->best_drivers(10);
        //var_dump($best_users);exit();

        $days = array();$names = array();$tripsAcheived = array();
        foreach ($best_users as $user) {
            if($user->drivers){
                $names[] =($user->drivers)?$user->drivers->name:'---';
                $tripsAcheived[] = $user->sum_trip_acheived;
            }
        }

        $titleY = 'عدد الرحلات المحققة';
        return view('admin.drivers_kpi.best_drivers',['title'=>$title,
            'chart' => $chart->build($names,'', $tripsAcheived, $titleY ,$chartTitle)]);

    }
    public function best_drivers_table( ){
        $title = 'أفضل مئة كابتن ';
        $y = new Kpi_driver();
        $best_users = $y->best_drivers(100);
        return view('admin.drivers_kpi.best_drivers_table',compact('best_users','title'));

    }
}
