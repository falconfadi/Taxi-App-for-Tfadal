<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Setting;
use App\Models\Sum_money;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $status = array();
    public function __construct()
    {
        parent::__construct();
        //1 if renew,0 if discount, 2 if add user discount, 3 if trip , if distance compensation ,
        $this->status = array('0'=>__('label.discount'),
            '1'=>__('label.renew'),
            '2'=>__('label.user_discount'),
            '3'=>__('label.trip_'),'4'=>__('label.distance_compensation'),'5'=>__('label.schedule_trip_discount')
                ,'6'=>__('label.female_captain_extra'));

    }
    public function index()
    {


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function show(Balance $balance)
    {
        //
    }


    public function  search (Request $request){
        //ajax search
        $title = __('menus.driver_balance');
        $U = new User();
        $drivers = $U->getAllDrivers();
        $users = $U->getAllUsers();
        $se = new Setting();
        $trCtrl = new TripController();
        $status = $trCtrl->status;
        $trips = Trip::all();

        $balance = 0;$oldBalance = 0;
        if ($request->ajax()) {
            $driver_id = $request->get('driver_id');
            $query = Balance::query();
            $sumMoney = new Sum_money();
            if($driver_id != 0){
                $query->where('driver_id',$driver_id );
                $balanceObj = $sumMoney->driverBalance($driver_id);
                if ($balanceObj)  $balance = $balanceObj->balance;

            }
            if($request->get('date')){
                $query->whereDate('created_at','>=', $request->get('date'));
                $oldBalance = ($sumMoney->balanceByDate($driver_id, $request->get('date')))?$sumMoney->balanceByDate($driver_id, $request->get('date'))->balance:0;
            }
            if($request->get('date_to'))
                $query->whereDate('created_at','<=', $request->get('date_to'));

            $data = $query->get();
            //$output = '';
            $output =  array('tbody'=>'','tfoot'=>'','balance'=>'');
            if (count($data) > 0) {
                // $output = '';
                //$output = array();
                $sum = 0;
                foreach ($data as $item) {
                    $output['tbody'] .= '<tr>';
                    //$inv->trip->driver->name." ".$inv->trip->driver->driver_details->father_name." ".$inv->trip->driver->driver_details->last_name;
                    $drivername = '';$url = '#';
                    if($item->driver){
                        $drivername = $item->driver->name." ".$item->driver->drivers_details->father_name." ".$item->driver->drivers_details->last_name;
                        $url = url('admin/drivers/view/'.$item->driver->drivers_details->id);
                    }
                    $output['tbody'] .= '<td> <a href="'.$url.'">'.$drivername.'</a></td>';
                    if( in_array($item->is_renew,[0,3])){
                        $sign="-";
                    }  else{
                        $sign="+";
                    }
                    $output['tbody'] .= '<td>'.$sign.$item->renew_amount.'</td>';
                    $output['tbody'] .= '<td>'.$this->status[$item->is_renew].'</td>';
                    $tripSerialNumber = ($item->trip_id != 0)?$item->trip->serial_num:'---';
                    $output['tbody'] .= '<td>'.$tripSerialNumber.'</td>';
                    $companyPercentage = ($item->trip_id != 0 && $item->trip->invoice)?$item->trip->invoice->company_percentage:'---';
                    $output['tbody'] .= '<td>'.$companyPercentage.'</td>';
                    $output['tbody'] .= '<td>'.$item->created_at.'</td>';
                    $output['tbody'] .= '</tr>';
                }
                $output['tfoot'] = '<tr><td ><b>'.__('label.balance').'</b></td><td><b>'.$balance.'</b></td><td></td><td></td><td></td><td></td></tr>';
                $output['balance'] = $oldBalance;
            } else {
                $output['tbody'] = '<li class="list-group-item">' . 'No results' . '</li>';
                $output['tfoot'] = '';
                $output['balance'] = '';
            }
            return $output;
        }
        return view('admin.balance.search',compact('drivers','users','title','trips','status'));
    }

    public function editBalance(Request $request)
    {
       // echo $request->new_balance;

        $x = Sum_money::where('driver_id',$request->driverId)->latest()->first();
        if($x){
            $x->balance = $request->new_balance;
            $x->update();
        }else{
            $today = Carbon::now();
            $y = new Sum_money();
            $y->amount =  0;
            $y->driver_id =  $request->driverId;
            $y->balance = $request->new_balance;
            $y->work_day = $today->toDateString();
            $y->num_of_renew_requests = 1;
            $y->save();
//            Session::flash('alert-danger',__('message.No_data'));
//            return redirect()->back();
        }
        Session::flash('alert-success',__('message.balance_is_edited'));
        return redirect()->back();


    }
}
