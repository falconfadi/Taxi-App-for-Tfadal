<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Sum_money;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function balanceByDriver(Request $request){
        $request->validate([
            'driver_id' => 'required'
        ]);
        $SumMoney = new Sum_money();
        $balance = $SumMoney->driverBalance($request->driver_id);
        if($balance){
            if($balance->balance > 0){
                $companyBalance = 0;
                $driverBalance = $balance->balance;
            }else{
                $companyBalance = -1 * $balance->balance;
                $driverBalance = 0;
            }
        }else{
            $companyBalance = 0;
            $driverBalance = 0;
        }
        $tripModel = new Trip();
        if(is_null($request->start_date)  || $request->start_date ==''){
            $today = Carbon::today();
            $trips = $tripModel->getTripsWithDetailsBetweenTwoDatesDriver($request->driver_id,$today, $today);
        } else{
            $trips = $tripModel->getTripsWithDetailsBetweenTwoDatesDriver($request->driver_id, $request->start_date, $request->end_date);
        }
        if($trips){
            $tripCompensation = 0;$driverCompensation = 0; $discounts = 0; $wholePrice =0; $netMoneyCaptain = 0; $sumOfTrips = 0;
            $companyPercentage = 0;
            foreach ($trips as $trip){
                $sumOfTrips++;
                $companyPercentage += ($trip->invoice)?$trip->invoice->company_percentage:0;
                if($trip->is_scheduled==1){
                    $scheduleTripDiscount = Balance::where('trip_id',$request->trip_id)->where('is_renew',5)->first();
                }else{
                    $scheduleTripDiscount = 0;
                }

                $driverCompensation += ($trip->driverCompensation)?$trip->driverCompensation->amount:0;
                if($trip->invoice) {
                    $discounts += $trip->invoice->discount + $scheduleTripDiscount;
                    //$price = $trip->invoice->price;
                    $wholePrice += $trip->invoice->price + $trip->invoice->discount;
                    $netMoneyCaptain += $wholePrice + $driverCompensation + $tripCompensation - $trip->invoice->company_percentage;
                }
//                else{
//                    $discounts = 0;
//                    //$price = 0;
//                    $wholePrice = 0;
//                    $netMoneyCaptain = 0;
//                }
            }
            return response()->json(
                [
                    'message' => 'balance',
                    'data' => [
                        'max_amount_to_stop_driver' => $this->setting->max_amount_to_stop_driver ,
                        'driver_balance' => $driverBalance,
                        'company_balance' =>  $companyBalance,
                        'sum_trips' => $sumOfTrips,
                        'sum_distance_compensation' => $driverCompensation,
                        'sum_trip_compensation' => 0,
                        'sum_company_percentage' =>  $companyPercentage,
                        'sum_net_money_captain' => $netMoneyCaptain,
                        'sum_whole_price' => $wholePrice ,
                        'sum_discounts' => $discounts
                    ]
                ]
            );
        }else{
            return response()->json(
                [
                    'message' => 'balance',
                    'data' => [
                        'max_amount_to_stop_driver' => $this->setting->max_amount_to_stop_driver ,
                        'driver_balance' => 0,
                        'company_balance' => 0,
                        'sum_trips' => 0,
                        'sum_distance_compensation' => 0,
                        'sum_trip_compensation' => 0,
                        'sum_company_percentage' => 0 ,
                        'sum_net_money_captain' => 0,
                        'sum_whole_price' => 0 ,
                        'sum_discounts' => 0
                    ]
                ]
            );
        }
    }
}
//0962128385
