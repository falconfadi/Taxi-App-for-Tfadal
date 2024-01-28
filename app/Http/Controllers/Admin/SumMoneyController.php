<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Balance;
use App\Models\POS;
use App\Models\Setting;
use App\Models\Sum_money;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SumMoneyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('auth:admin');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = __('menu.SumOfMoney');
        $requests = Sum_money::with('driver')->get();

        $drivers = User::where('is_driver',1)->whereNotNull('fcm_token')->get();

        $S = new  Setting();
        $maxBalance = $S->getSetting()->max_amount_to_stop_driver;

        return view('admin.money.index',compact('requests','title','drivers','maxBalance'));
    }


    public function sumMoneyAcheivedPerMonth(Request $request){

        $title = __('menu.SumOfMoney');
        $X = new Sum_money();
        $result = $X->sumMoneyAcheivedPerMonth($request->driver_id, $request->month, $request->year);
        $month = date("F", strtotime($request->month));
        $date = $request->year."-".$month;
        $driver_name = User::find($request->driver_id)->name;
        return view('admin.money.month',compact('result','driver_name','date','title'));
    }

    public function sumMoneyAcheivedPerDay($driver_id,$price )
    {
        $today = Carbon::now();
        //if driver have record today
        $x = Sum_money::where('driver_id',$driver_id)->where('work_day', $today->toDateString())->first();
        if($x){
            $x->amount += $price;
            //delete the company percentage of trip from the balance of driver
            $x->balance -= $price;
            $x->save();
            //return true;
        }
        else{
            //if driver have a record before
            $x = Sum_money::where('driver_id',$driver_id)->latest()->first();
            $y = new Sum_money();
            if($x){
                $y->amount += $price;
                $y->driver_id =  $driver_id;
                //get balance from last work day
                $y->balance = $x->balance - $price;
                $y->work_day = $today->toDateString();
                $y->save();
            }
            else{
                //add new record for this driver
                $y->amount +=  $price;
                $y->driver_id =  $driver_id;
                $y->balance -=  $price;
                $y->work_day = $today->toDateString();
                $y->save();
            }
            //return true;
        }
    }

    public function renewBalanceForm(){
        $title = __('label.renew_balance');
        $requests = Sum_money::with('driver')->get();

        $drivers = User::where('is_driver',1)->whereNotNull('fcm_token')->get();

        $S = new  Setting();
        $maxBalance = $S->getSetting()->max_amount_to_stop_driver;

        return view('admin.money.renew',compact('requests','title','drivers','maxBalance'));

    }

    public function renew_balance(Request $request){

        $b = new Balance();
        $sumMoney = new Sum_money();

        //renew by POS
        if($request->back_url) {
            $driver = User::where('phone',$request->phone)->where('is_driver', 1)->first();
            if($driver) {
                $b->addBalanceRecord($driver->id, $request->amount, '',1,false, 0);
                $sumMoney->updateMoney($driver->id, $request->amount);

                $pos = new POS();
                $pos->saveRecord($driver->id, $request->amount);

//                Session::flash('alert-success','تم تجديد الرصيد');
//                return redirect()->back();
            }
            else {
                Session::flash('alert-danger',__('message.No_data'));
                return redirect()->back();
            }
        }
        else {
            $driver = User::find($request->driver_id);
             if($driver){
                 $b->addBalanceRecord($request->driver_id, $request->amount, '',1,$request->file('image'), 0);
                 $sumMoney->updateMoney($request->driver_id, $request->amount);

                 $pos = new POS();
                 $pos->saveRecord($driver->id, $request->amount);

                 Session::flash('alert-success','تم تجديد الرصيد');
                 return redirect()->back();

            }else{
                 Session::flash('alert-danger',__('message.No_data'));
                 return redirect()->back();
            }
        }
    }


    public function test(){
//        $user_id = 25 ;
//        $this->sumMoneyAcheivedPerDay($user_id,25);
//        $f = new User();
//        $r = $f->getAvailableDrivers1();
//        var_dump($r);
//        $sumMoney = new Sum_money();
//        $sumMoney->updateMoney(12, 15);



    }
}
