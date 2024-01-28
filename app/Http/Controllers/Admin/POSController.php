<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Balance;
use App\Models\POS;
use App\Models\Setting;
use App\Models\Sum_money;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class POSController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title =  __('menus.pos');

        return view('admin.pos.search',compact('title'));
    }


    public function  search (Request $request){
        //ajax search
        $title =  __('menus.pos');
        $pos = new POS();
        $poses = $pos->getPOS();
        //$trips = Trip::all();
        $pos_id = $request->get('pos_id');
        $balance = 0;
        if ($request->ajax()) {
            $query = POS::query();
            //$sumMoney = new Sum_money();
            if($pos_id != 0){
                $query->where('pos_id', $pos_id );
                //$balanceObj = $sumMoney->driverBalance($driver_id);
                //if ($balanceObj)  $balance = $balanceObj->balance;
            }
            if($request->get('date')){
                $query->whereDate('created_at','>=', $request->get('date'));
                //$oldBalance = ($sumMoney->balanceByDate($driver_id, $request->get('date')))?$sumMoney->balanceByDate($driver_id, $request->get('date'))->balance:0;
            }
            if($request->get('date_to')){
                $query->whereDate('created_at','<=', $request->get('date_to'));
            }

            $data = $query->get();
            $output =  array('tbody'=>'','balance'=>'');
            if (count($data) > 0) {
                // $output = '';
                //$output = array();
                $sum = 0;
                foreach ($data as $item) {
                    $output['tbody'] .= '<tr>';
                    $output['tbody'] .= '<td>'.$item->admin->name.'</td>';
                    $drivername = '';$url = '#';
                    if($item->driver){
                        $drivername = $item->driver->name." ".$item->driver->drivers_details->father_name." ".$item->driver->drivers_details->last_name;
                        $url = url('admin/drivers/view/'.$item->driver->drivers_details->id);
                    }
                    $output['tbody'] .= '<td> <a href="'.$url.'">'.$drivername.'</a></td>';
                    $sum += $item->amount;
                    $output['tbody'] .= '<td>'.$item->amount.'</td>';
                    $output['tbody'] .= '<td>'.$item->created_at.'</td>';
                    $output['tbody'] .= '</tr>';
                }
                $output['tfoot'] = '<tr><td>المجموع</td><td></td><td></td><td>'.$sum.'</td></tr>';
                $output['balance'] = '';
            } else {
                $output['tbody'] = '<li class="list-group-item">' . 'No results' . '</li>';
                $output['tfoot'] = '';
                $output['balance'] = '';
            }
            return $output;
        }
        return view('admin.pos.search',compact('title','poses'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car_model  $car_model
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {

        $car = POS::find($id);

        if($car )
        {
            $user = User::find($car->driver_id)->delete();
            $car->delete();
            $driver = Driver::where('user_id',$car->driver_id)->delete();
            //$del_driver = Driver::find($id)->delete();
        }

        return back()->with('success','Car deleted successfully');
    }

}
