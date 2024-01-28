<?php

namespace App\Http\Controllers\Admin;

use App\Charts\BarChart;
use App\Charts\BarChartMulti;
use App\Charts\UsersChart;
use App\Http\Controllers\Controller;
use App\Models\Daily_kpis;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class KpiTripController extends Controller
{
    public function index()
    {
        $title = 'احصائيات الرحلات';
        //$kpi_users = Kpi_users::with('users')->get();
        return view('admin.trip_kpi.trips',compact('title'));
    }

    public function trips_form($url){
        //$url = $_GET['url'];
        if($url){
            $fullURL = 'admin/'.$url;
            $title = 'إحصائيات الرحلات';
            //$url = 'admin/num_of_trips';
            return view('admin.trip_kpi.trips_form',compact('title','fullURL'));
        }
        //var_dump($url);exit();
    }

    public function num_of_trips(BarChart $chart,Request $request){
        //var_dump($request->all());exit();
       // $fromDate = $request; $toDate = '';
        $title = ' الطلبات بين تاريخين';
        $x = new Trip();
        $trips = $x->getTripsBetweenTwoDatesAll($request->from , $request->to);
        $driverNames = array();$numOfTrips = array();$sumTrips = 0;
        foreach ($trips as $trip)
        {
            $sumTrips += $trip->trip_count;
            $numOfTrips[] = $trip->trip_count;
            //echo $trip->driver_id;
            $driverNames[] = User::find($trip->driver_id)->name;
        }

        $chartTitle = 'عدد الرحلات بالنسبة للكباتن';
        $titleY = 'عدد الرحلات';
        return view('admin.trip_kpi.num_of_trips',['title'=>$title,'from' => $request->from , 'to' => $request->to,'sumTrips'=>$sumTrips,
            'chart' => $chart->build($driverNames,'', $numOfTrips, $titleY ,$chartTitle)]);
    }

    public function trips_prob(BarChart $chart,Request $request){
        //var_dump($request->all());exit();
        // $fromDate = $request; $toDate = '';
        $title = 'إحصائيات الرحلات';
        $x = new Trip();

        $trips = $x->getTripsBetweenTwoDatesPerDay($request->from , $request->to);

        $days = array();$numOfTrips = array();$sumTrips = 0;
        foreach ($trips as $trip)
        {
            $sumTrips += $trip->trip_count;
            $numOfTrips[] = $trip->trip_count;
            //echo $trip->driver_id;
            $days[] = $trip->trip_day;
        }
        $chartTitle = 'عدد الرحلات بالنسبة للأيام';
        $titleY = 'عدد الرحلات';
        return view('admin.trip_kpi.trips_prob',['title'=>$title,'from' => $request->from , 'to' => $request->to,'sumTrips'=>$sumTrips,
            'chart' => $chart->build($days,'', $numOfTrips, $titleY ,$chartTitle)]);
    }

    public function trips_prob_cancelled(BarChart $chart,Request $request){
        //var_dump($request->all());exit();
        // $fromDate = $request; $toDate = '';
        $title = 'إحصائيات الرحلات الملغية';
        $x = new Trip();

        $trips = $x->getCancelledTripsBetweenTwoDatesPerDay($request->from , $request->to);

        $days = array();$numOfTrips = array();$sumTrips = 0;
        foreach ($trips as $trip)
        {
            $sumTrips += $trip->trip_count;
            $numOfTrips[] = $trip->trip_count;
            //echo $trip->driver_id;
            $days[] = $trip->trip_day;
        }
        $chartTitle = 'عدد الرحلات الملغية بالنسبة للأيام';
        $titleY = 'عدد الرحلات';
        return view('admin.trip_kpi.trips_prob_cancelled',['title'=>$title,'from' => $request->from , 'to' => $request->to,'sumTrips'=>$sumTrips,
            'chart' => $chart->build($days,'', $numOfTrips, $titleY ,$chartTitle)]);
    }

    public function trips_money(BarChartMulti $chart,Request $request){

        $title =$chartTitle= 'دخل الشركة من الرحلات';
        $x = new Invoice();
        $trips = $x->getMoneyBetweenTwoDates($request->from , $request->to);
        //var_dump($trips);exit();
        $companyFunds = array();$driversFunds = array();$sumTrips = 0;
        //$x = new Setting();
        //$company_percentage = $this->setting->company_percentage;
        $sumMoney = 0;
        foreach ($trips as $invoice)
        {
            //var_dump($invoice);echo "------------<br>";
            //$sumMoney += $invoice->sum_money;
            $companyFunds[] = round($invoice->company_percentage,0);
            $driversFunds[] = round($invoice->sum_money,0);
            $days[] = $invoice->trip_day;
            //echo $invoice->trip->id;echo "<br>------------<br>";
           // $driverNames[] = User::find($invoice->trip->driver_id)->name;
        }
        $titleY = 'أرباح الشركة';
        $titleY2 = 'أرباح الكباتن';
        return view('admin.trip_kpi.trips_money',['title'=>$title,'from' => $request->from , 'to' => $request->to,
            'companyFunds'=> $companyFunds,'driversFunds'=>$driversFunds,
            'chart' => $chart->build($days,'الأيام', $companyFunds, $titleY ,$driversFunds,$titleY2,$chartTitle)]);
    }

    public function doKPIs($trip, $price){
        // KPI....some money achieved...user
        $x = new KpiUsersController();
        $x->sumMoneyPaid($trip->user_id , $price);

        // KPI ....some money achieved...driver
        $y = new KpiDriversController();
        $y->sumMoneyAchieved($trip->driver_id , $price);

        // KPI ....... sum Trip Acheived ...users
        $x = new KpiUsersController();
        $x->sumTripAcheived($trip->user_id );

        // KPI ....... sum Trip Acheived ...drivers
        $x = new KpiDriversController();
        $x->sumTripAcheived($trip->driver_id);

        //daily kpis
        $x = new Daily_kpis();
        $x->updateDailyKPI($trip->driver_id,$trip->id);
    }
}
