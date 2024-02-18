<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\KpiDriversController;
use App\Http\Controllers\Admin\KpiUsersController;
use App\Http\Controllers\Admin\SumMoneyController;
use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Car;
use App\Models\Coordinate;
use App\Models\Counter;
use App\Models\Driver_rejected_trip;
use App\Models\Invoice;

use App\Models\MultiTripOffers;
use App\Models\Notification;
use App\Models\Offers;
use App\Models\Setting;
use App\Models\Sum_money;
use App\Models\Trip;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Damascus');
    }
    //api calculate_invoice
    public function calculateInvoice(Request $request)
    {
        $request->validate([
            'trip_id' =>'required',
        ]);

        //calculate distance
        //calculate time
        $trip = Trip::with('carType')->where('id',$request->trip_id)->first();
        if ($trip)
        {
            $price = 0;
            //$realDuration = $endTime->diffInMinutes($startTime);
            //$expectedDuration = $realDuration;
            $expectedDuration = $trip->trip_details->expected_duration;

            $counter = new Counter();
            $record = $counter->getLatestCounter($trip->id , $trip->driver_id);
            if($record) {
                $priceAndDiscount = $this->calculatePrice($record->whole_distance, $expectedDuration, $trip);
            }else{
                $priceAndDiscount = $this->calculatePrice(0, $expectedDuration, $trip);
            }
            $price = $priceAndDiscount[0];
            $discount = $priceAndDiscount[1];

            //$totalPrice = $price + $discount;
            $totalPrice = $this->getTotalPrice($price, $discount);

            //calculate company percentage
            $set = new Setting();
            $companyPercentage = $set->getCompanyPercentage($totalPrice);

            //save invoice
            $invoice = new Invoice();
            $executed = $invoice->addInvoice($request->trip_id, $price, $discount, $companyPercentage);

            if($executed)
            {
                //calculate money per day for driver
                $z = new SumMoneyController();
                $z->sumMoneyAcheivedPerDay($trip->driver_id,$companyPercentage);

                $balanceItemsArray = array();
                $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$companyPercentage,'ishaar'=>'','is_renew'=>3,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
                array_push($balanceItemsArray, $balanceItem);
                if($this->tSetting->gift_captain_girl > 0){
                    $giftCaptainGirl = $this->tSetting->gift_captain_girl;
                    if($trip->driver->gender==2){
                        $sumMoney = new Sum_money();
                        $sumMoney->updateMoney($trip->driver_id, $giftCaptainGirl);
                        $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$giftCaptainGirl,'ishaar'=>'','is_renew'=>6,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
                        array_push($balanceItemsArray, $balanceItem);
                    }
                }
                if($this->tSetting->gift_captain_male > 0){
                    $maleCaptainGirl = $this->tSetting->gift_captain_male;
                    if($trip->driver->gender==1){
                        $sumMoney = new Sum_money();
                        $sumMoney->updateMoney($trip->driver_id, $maleCaptainGirl);
                        $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$maleCaptainGirl,'ishaar'=>'','is_renew'=>7,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
                        array_push($balanceItemsArray, $balanceItem);
                    }
                }
                if($discount > 0){
                    //add user discount to driver balance
                    $sumMoney = new Sum_money();
                    //add schedule trip discount
                    if($trip->is_scheduled==1 && $this->tSetting->schedule_trip_discount>0){
                        $scheduleTripDiscount = $this->tSetting->schedule_trip_discount;
                        $sumMoney->updateMoney($trip->driver_id, $discount);
                        //$b->addBalanceRecord($trip->driver_id, $scheduleTripDiscount, '', 5, false, $trip->id);
                        $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$scheduleTripDiscount,'ishaar'=>'','is_renew'=>5,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
                        array_push($balanceItemsArray, $balanceItem);

                        $offerDiscount =  $discount-$scheduleTripDiscount;
                        if($offerDiscount>0){
                            //$b->addBalanceRecord($trip->driver_id, $offerDiscount, '', 2, false, $trip->id);
                            $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$offerDiscount,'ishaar'=>'','is_renew'=>2,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
                            array_push($balanceItemsArray, $balanceItem);
                        }
                    }else{
                        $sumMoney->updateMoney($trip->driver_id, $discount);
                        //$b->addBalanceRecord($trip->driver_id, $discount, '', 2, false, $trip->id);
                        $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$discount,'ishaar'=>'','is_renew'=>2,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
                        array_push($balanceItemsArray, $balanceItem);
                    }
                }
                Balance::insert($balanceItemsArray);
//                $b = new Balance();
//                $b->addBalanceRecord($trip->driver_id, $companyPercentage, '', 3, false, $trip->id);
//
//                if($this->tSetting->gift_captain_girl > 0){
//                    if($trip->driver->gender==2){
//                        $sumMoney = new Sum_money();
//                        $sumMoney->updateMoney($trip->driver_id, $this->tSetting->gift_captain_girl);
//                    }
//                }
//                if($discount > 0){
//                    //add user discount to driver balance
//                    $sumMoney = new Sum_money();
//                    //add schedule trip discount
//                    if($trip->is_scheduled==1 && $this->tSetting->schedule_trip_discount>0){
//                        $scheduleTripDiscount = $this->tSetting->schedule_trip_discount;
//                        //$discount += $scheduleTripDiscount;
//                        $sumMoney->updateMoney($trip->driver_id, $discount);
//                        $b->addBalanceRecord($trip->driver_id, $scheduleTripDiscount, '', 5, false, $trip->id);
//                        $offerDiscount =  $discount-$scheduleTripDiscount;
//                        if($offerDiscount>0){
//                            $b->addBalanceRecord($trip->driver_id, $offerDiscount, '', 2, false, $trip->id);
//                        }
//                    }else{
//                        $sumMoney->updateMoney($trip->driver_id, $discount);
//                        $b->addBalanceRecord($trip->driver_id, $discount, '', 2, false, $trip->id);
//                    }
//                }

            }

            $realDuration = 0;
            $endTime = Carbon::now();
            if(!is_null($trip->start_date)){
                $realDuration = ceil($trip->getDifferenceOfTimeInMinutes($endTime, $trip->start_date));
            }else{
                $realDuration = ceil($trip->getDifferenceOfTimeInMinutes($endTime, $trip->arrive_to_customer_time));
            }

            //send notification to user
            $notificationObj = new NotificationsController();
            $notificationObj->sendInvoiceNotification($totalPrice, $trip, $expectedDuration, $request->trip_distance, $price, $discount);

            return response()->json([
                "success" => true,
                "message" => "Invoice",
                'data'=> [
                    'time' => round($realDuration, 1),
                    'distance'=>round($request->trip_distance, 2),
                    'price_before_discount' => $totalPrice ,
                    'net_price'=>$price,
                    'discount' =>$discount,
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لا يوجد',
                    'english_error' => ' No trip!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    //get all invoices for  a user or driver
    public function invoicesByUser(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
        ]);
        $user = User::find($request->user_id);
        $invoices = array();
        if($user->is_driver==0)
        {
            $invoices = Invoice::whereHas('trip',function($subQ) use ($request){
                $subQ->where('user_id',$request->user_id);
            })->with('trip')->get();
        }
        else{
            $invoices = Invoice::whereHas('trip',function($subQ) use ($request){
                $subQ->where('driver_id',$request->user_id);
            })->with('trip')->get();
        }

        if ($invoices)
        {
            $total_amount = 0;
            foreach($invoices as $invoice)
            {
                $total_amount += $invoice->price;
                $invoice->driver_name = (User::find($invoice->trip->driver_id))?User::find($invoice->trip->driver_id)->name:'';
            }

            return response()->json([
                "success" => true,
                "message" => "Invoices",
                'data'=> [
                    'invoices' => $invoices,
                    'total_amount' => round($total_amount,2)
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لا يوجد',
                    'english_error' => ' No invoice!!',
                    'arabic_result' => '',
                    'english_result' => '',
                    'total_amount' =>0
                ]
            ]);
        }
    }


    //get all invoices details of a trip
    public function tripwithInvoiceDetails(Request $request)
    {
        $request->validate([
            'trip_id' =>'required',
        ]);
        $trip = Trip::with('invoice','driverCompensation')->find($request->trip_id);
        if($trip->is_scheduled==1){
            $scheduleTripDiscount = Balance::where('trip_id',$request->trip_id)->where('is_renew')->first();
        }else{
            $scheduleTripDiscount = 0;
        }
        $tripCompensation = 0;
        $driverCompensation = ($trip->driverCompensation)?$trip->driverCompensation->amount:0;
        if($trip->invoice) {
            $discounts  = $trip->invoice->discount + $scheduleTripDiscount;
            $price = $trip->invoice->price;
            $wholePrice = $trip->invoice->price+$trip->invoice->discount;
            $netMoneyCaptain = $wholePrice + $driverCompensation + $tripCompensation - $trip->invoice->company_percentage ;
        }else{
            $discounts = 0;
            $price = 0;
            $wholePrice = 0;
            $netMoneyCaptain = 0;
        }

        if ($trip)
        {
//            foreach($invoices as $invoice)
//            {
//                $total_amount += $invoice->price;
//                $invoice->driver_name = (User::find($invoice->trip->driver_id))?User::find($invoice->trip->driver_id)->name:'';
//            }

            return response()->json([
                "success" => true,
                "message" => "trip data",
                'data'=> [
                    'location_from' => $trip->location_from,
                    'location_to' => $trip->location_to,
                    'trip_date' => $trip->trip_date,
                    'serial_num' => $trip->serial_num,
                    'distance_compensation' => $driverCompensation,
                    'trip_compensation' => $tripCompensation,
                    'company_percentage' => $trip->invoice->company_percentage ,
                    'net_money_captain' => $netMoneyCaptain,
                    'whole_price' => $wholePrice ,
                    'discounts' => $discounts

                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لا يوجد',
                    'english_error' => ' No Data!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }


    public function getDifferenceOfTimeInMinutes($start_time, $end_time)
    {
        //'2020-02-10 04:04:26'
        //'2020-02-11 04:36:56'
        $minutes = abs(strtotime($end_time) - strtotime($start_time)) / 60;

        return $minutes;
    }

    public function calculatePrice($distance  ,$expectedDuration, $trip)
    {
        $price = 0;
        //$oldPrice = round($distance *  $trip->carType->price ,2)+ $googleCost;
        //$oldPrice = $this->calculateInvoice($distance, )
        $T = new TripController();
        $oldPrice = $T->estimatePrice($distance, $expectedDuration, $trip);
        //check if there is offer
        $O = new Offers();
        $discount = $O->checkDiscountAvailabilty($trip, $oldPrice);

        $result = (int)($oldPrice-$discount);
        $price = (($result)>0)?$result:0;
        $priceAndDiscount = array();
        //price
        $priceAndDiscount[0] = $price;
        //discount
        $priceAndDiscount[1] = $discount;
        return $priceAndDiscount;
    }

    public function test()
   {

        $companyPercentage = 1000;
        $discount = 3000;
        $trip = Trip::find(647);
       //calculate money per day for driver
       $z = new SumMoneyController();
       $z->sumMoneyAcheivedPerDay($trip->driver_id,$companyPercentage);

       $b = new Balance();
       $balanceItemsArray = array();
       //$b->addBalanceRecord($trip->driver_id, $companyPercentage, '', 3, false, $trip->id);
       $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$companyPercentage,'ishaar'=>'','is_renew'=>3,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
       array_push($balanceItemsArray, $balanceItem);
       if($this->tSetting->gift_captain_girl > 0){
           $giftCaptainGirl = $this->tSetting->gift_captain_girl;
           if($trip->driver->gender==2){
               $sumMoney = new Sum_money();
               $sumMoney->updateMoney($trip->driver_id, $this->tSetting->gift_captain_girl);
               $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$giftCaptainGirl,'ishaar'=>'','is_renew'=>6,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
               array_push($balanceItemsArray, $balanceItem);
           }
       }
       if($discount > 0){
           //add user discount to driver balance
           $sumMoney = new Sum_money();
           //add schedule trip discount
           if($trip->is_scheduled==1 && $this->tSetting->schedule_trip_discount>0){
               $scheduleTripDiscount = $this->tSetting->schedule_trip_discount;
               $sumMoney->updateMoney($trip->driver_id, $discount);
               //$b->addBalanceRecord($trip->driver_id, $scheduleTripDiscount, '', 5, false, $trip->id);
               $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$scheduleTripDiscount,'ishaar'=>'','is_renew'=>5,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
               array_push($balanceItemsArray, $balanceItem);
               $offerDiscount =  $discount-$scheduleTripDiscount;
               if($offerDiscount>0){
                   //$b->addBalanceRecord($trip->driver_id, $offerDiscount, '', 2, false, $trip->id);
                   $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$offerDiscount,'ishaar'=>'','is_renew'=>2,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
                   array_push($balanceItemsArray, $balanceItem);
               }
           }else{
               $sumMoney->updateMoney($trip->driver_id, $discount);
               //$b->addBalanceRecord($trip->driver_id, $discount, '', 2, false, $trip->id);
               $balanceItem = ['driver_id'=>$trip->driver_id,'renew_amount'=>$discount,'ishaar'=>'','is_renew'=>2,'image'=>'','trip_id'=>$trip->id,'created_at'=>Carbon::now()];
               array_push($balanceItemsArray, $balanceItem);
           }
       }
       Balance::insert($balanceItemsArray);

   }
   public function test1(){

       $trip = new Trip();
        echo Carbon::today();
       $driverCoordinates = $trip->getTripsWithDetailsBetweenTwoDatesDriver( 2954,Carbon::today(),Carbon::today());
       var_dump($driverCoordinates);
    }





}
