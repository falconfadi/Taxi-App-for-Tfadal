<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\DriverCompensation;
use App\Models\Sum_money;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DriverCompensationController extends Controller
{

    public function index()
    {
        $title = __("menus.driver_compensation");
       // $driverCompensation = DriverCompensation::all();
        $driverCompensationModel = new DriverCompensation();
        $driverCompensation = $driverCompensationModel->getlastNItem(50);
        $distances = array();
        foreach ($driverCompensation as $item){
            $trip = Trip::find($item->trip_id);
            //if($trip)
            //$distances[$item->trip_id] ='';// $driverCompensationModel->distanceForCompensation($item->latitude, $item->longitude,$trip->latitude_from, $trip->longitude_from );
        }
        return view('admin.driver_compensation.index',compact('title','driverCompensation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFaqRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    public function destroy($id)
    {
        $res = DriverCompensation::find($id)->delete();
        //return back()->with('success','User deleted successfully');
        return redirect('admin/driver-compensation');
    }

    public function edit($tripSerialNum)
    {
        $title = "تعديل تعويض المسافة";
        $trip = Trip::where('serial_num',$tripSerialNum)->first();
        if($trip)
        {
            //echo $distance."-";
            //driver compensation
            $comp = DriverCompensation::where('trip_id',$trip->id)->first();
            $compensationPerKilo  = $this->setting->compensation_driver_per_kilo;
            //$driverCompensation = $compensationPerKilo * $distance;
            //$amount = $c->roundUp($driverCompensation);

//            $comp->distance = $distance;
//            $comp->trip_id = $trip->id;
//            $comp->amount = $amount;
//            $comp->save();
//
//            $b = new Balance();
//            $sumMoney = new Sum_money();
            //$b->addBalanceRecord($trip->driver_id, $amount, '',4,false, $trip->id);
            return view('admin.driver_compensation.edit',compact('title','comp','trip'));
        }

    }


    public function update(Request $request)
    {
        $comp = DriverCompensation::find($request->id);
        $c = new \App\Http\Controllers\Api\CarController();
        $trip = Trip::find($comp->trip_id);
        $distance = $c->openStreetMapDistance($request->input('latitude'),$request->input('longitude'),$trip->latitude_from,$trip->longitude_from);

        $comp->distance = $distance;
        $comp->latitude = $request->input('latitude');
        $comp->longitude = $request->input('longitude');
        $compensationPerKilo  = $this->setting->compensation_driver_per_kilo;
        $driverCompensation = $compensationPerKilo * $distance;
        $amount = $c->roundUp($driverCompensation);
        $comp->amount = $amount;
        //$comp->save();

        if ($comp->save()) {
            Session::flash('alert-success','Car Type has been Updated');
            return redirect('admin/update-compensation/'.$trip->serial_num);
        } else {
            Session::flash('alert-success','Car Type Not Updated !!');
            return redirect('admin/update-compensation/'.$trip->serial_num);
        }
    }


}
