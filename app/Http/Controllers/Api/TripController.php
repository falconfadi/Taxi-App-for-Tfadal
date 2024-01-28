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
use App\Models\Daily_kpis;
use App\Models\Driver;
use App\Models\Driver_rejected_trip;
use App\Models\DriverAlert;
use App\Models\DriverCompensation;
use App\Models\FarwayRegion;
use App\Models\Invoice;
use App\Models\Notification;
//use App\Models\Offers;
use App\Models\Rate_trip;

use App\Models\Setting;
use App\Models\SharedTrip;

use App\Models\Sum_money;
use App\Models\TimeToArriveDriver;
use App\Models\Trip;
use App\Models\TripDetails;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\NotificationsController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use stdClass;

class TripController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Damascus');
    }
    //[user]
    public function add_new_trip(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
        ]);
        $trip = new Trip();
        $trip->user_id = $request->user_id;
        $trip->latitude_from = $trip->truncate($request->latitude_from,10);
        $trip->longitude_from = $trip->truncate($request->longitude_from,10);
        $trip->latitude_to =  $trip->truncate($request->latitude_to,10);
        $trip->longitude_to = $trip->truncate($request->longitude_to,10);
        $trip->location_from = $request->location_from;
        $trip->location_to = $request->location_to;
        $trip->car_type_id = ($request->car_type_id)?$request->car_type_id:'13';
        //start trip from user
        $trip->trip_date = Carbon::now();
        //$trip->google_cost = $request->google_cost;
        $trip->silence_trip = ($request->silence_trip)?$request->silence_trip:0;
        $trip->enable_discount = 0;
        $trip->note = ($request->note)?$request->note:'';
        //second phone number for user
        $trip->second_number =  ($request->second_number)?$request->second_number:'';
        $trip->is_multiple =0;

        //get last serial number and generate one
        //$t = new Trip();
        $trip->serial_num = $trip->getLastSerialNumber();
        //if user already has an active trip
        $haveTrip = Trip::whereIn('status',[0,1,2,3])->where('is_scheduled',0)->where('user_id',$request->user_id)->first();
        if($haveTrip) {
            return response()->json([
                "success" => false,
                "message" => "successfully added",
                'data' => [
                    'arabic_error' => '',
                    'english_error' => '',
                    'arabic_result' => 'عذراً لديك رحلة حالية ',
                    'english_result' => 'You already have active trip',
                    'trip_id' => $trip->id
                ]
            ]);
        }

        if ($trip->save())
        {
            //save trip details
            $tDetails = new TripDetails();
//            if($request->expected_distance==0 || is_null($request->expected_distance) || $request->expected_duration==0 || is_null($request->expected_duration)){
                //$C = new CarController();
//                $tDetails->expected_distance = round($C->directDistance($trip->latitude_from, $trip->longitude_from, $trip->latitude_to, $trip->longitude_to,1.2),4);
//                $tDetails->expected_duration = round($C->expectedDuration($tDetails->expected_distance ),4);
            if($request->expected_distance && !is_null($request->expected_distance) && $request->expected_distance !=0 ){
                $tDetails->expected_distance = round($request->expected_distance,4);
            }else{
                $carObj = new CarController();
                $tDetails->expected_distance = round(floatval($carObj->directDistance($trip->latitude_from, $trip->longitude_from, $trip->latitude_to, $trip->longitude_to, 1.4)),4);
            }

            if($request->expected_price && !is_null($request->expected_price) && $request->expected_price !=0 ){
                $tDetails->expected_price = $request->expected_price;
            }else{
                $tDetails->expected_price = $this->preEstimatePrice($request->expected_distance , (int)$trip->carType->price);
            }

            $tDetails->expected_duration = round($request->expected_duration,4);
            $tDetails->trip_id = $trip->id;
            $tDetails->ip_user = \Request::ip();
            $tDetails->save();
            //send notification to drivers
            $this->sendNotificationsToDrivers($trip->id, 0, $request->trip_price, $request->trip_distance,0);
            //if user share his trip
            if(is_numeric($request->share_user_id) && !is_null($request->share_user_id)){
                $obj = new AuthController();
                $obj->shareTripAndSendNotification($trip->user_id , $request->share_user_id, $trip->id );
            }
            return response()->json([
                "success" => true,
                "message" => "successfully added",
                'data'=> [
                    'arabic_error'=>'',
                    'english_error'=>'',
                    'arabic_result'=>'تم إضافة رحلة ',
                    'english_result'=>'successfully added',
                    'trip_id'=>$trip->id,
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لم يتم إضافة رحلة',
                    'english_error' => 'trip Not added!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    //[user]
    public function add_schedule_trip(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
        ]);
        $trip = new Trip();
        $timeOfTrip = $trip->getDifferenceOfTimeInMinutes($request->trip_date,Carbon::now());
        if($timeOfTrip<30){
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => __('message.trip_must_be_after_30_mins'),
                    'english_error' => 'Sorry, Scheduled trip must be after 30 minutes !!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }else{
            $trip->user_id = $request->user_id;
            $trip->latitude_from = $request->latitude_from;
            $trip->longitude_from = $request->longitude_from;
            $trip->latitude_to = $request->latitude_to;
            $trip->longitude_to = $request->longitude_to;
            $trip->location_from = $request->location_from;
            $trip->location_to = $request->location_to;
            $trip->car_type_id = $request->car_type_id;
            $trip->trip_date = $request->trip_date;
            $trip->status = 6;
            $trip->silence_trip = ($request->silence_trip)?$request->silence_trip:0;
            $trip->enable_discount = 0;
            $trip->is_scheduled = 1;
            $trip->note = ($request->note)?$request->note:'';
            //second phone number for user
            $trip->second_number =  ($request->second_number)?$request->second_number:'';
            $trip->is_multiple = 0;

            //get last serial number and generate one
            $t = new Trip();
            $trip->serial_num = $t->getLastSerialNumber();

            if ($trip->save())
            {
                //save trip details
                $tDetails = new TripDetails();
                $C = new CarController();
                $tDetails->expected_distance = round($C->directDistance($trip->latitude_from, $trip->longitude_from, $trip->latitude_to, $trip->longitude_to,1.2),4);
                $tDetails->expected_duration = round($C->expectedDuration($tDetails->expected_distance ),4);
                if($request->expected_price && !is_null($request->expected_price) && $request->expected_price !=0 ){
                    $tDetails->expected_price = round($request->expected_price,4);
                }else{
                    //$tripObj = new TripController();
                    $tDetails->expected_price = $this->preEstimatePrice($request->expected_distance , (int)$trip->carType->price);
                }
                $tDetails->trip_id = $trip->id;
                $tDetails->ip_user = \Request::ip();
                $tDetails->save();

                //send notification to drivers
                $this->sendNotificationsToDrivers($trip->id, 0, $request->trip_price, $request->trip_distance,0);
                return response()->json([
                    "success" => true,
                    "message" => "successfully added",
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>'تم إضافة رحلة مجدولة ',
                        'english_result'=>'successfully added',
                        'trip_id'=>$trip->id
                    ]
                ]);
            }
            else {
                return response()->json([
                    "success" => false,
                    'data' => [
                        'arabic_error' => 'لم يتم إضافة رحلة',
                        'english_error' => 'Scheduled trip Not added!!',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
            }
        }

    }

    public function getNextCircle(Request $request){
        $request->validate([
            'trip_id' =>'required',
        ]);
        $trip = Trip::find($request->trip_id);
        if($trip){

            if($trip->driver_id ==0){
                $circleNumber = $trip->circle_number+1;
                $this->sendNotificationsToDrivers($request->trip_id, $circleNumber, $request->trip_price, $request->trip_distance, $trip->is_multiple);

                //update cirle number
                $trip->circle_number = $circleNumber;
                $trip->update();
            }
        }else{
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => 'No data',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function sendNotificationsToDrivers($trip_id, $circle, $trip_price, $trip_distance,$is_multiple){
        $trip = Trip::find($trip_id);
        $notificationObj = new NotificationsController();
        $X = new User();
        $driversFromOtherType = array();
        //$drivers_ = array();
        $drivers = $X->getDriversByCarType($trip->car_type_id);

        $carType =  Car_type::find($trip->car_type_id) ;

        $distances = array();
        foreach ($drivers as $driver){
            $rejectedByDriver = Driver_rejected_trip::where('trip_id',$trip_id)->where('driver_id',$driver->id)->first();
            //if driver reject this trip before
            if(!$rejectedByDriver){
                //if driver already has an active trip
                $Tr = new Trip();
               // $haveTrip = $Tr->getActiveTripByDriverId($driver->id);
                $haveTrip = Trip::whereIn('status',[1,2,3])->where('driver_id',$driver->id)->first();
                $carObj = new CarController();
                //$set = new Setting();
                $s =  $this->setting;
                if(!$haveTrip){
                    //take the nearest driver
                    if( $driver->latitude != 0 && is_numeric($driver->latitude)){
                        //$dist = $carObj->googleMapDistance($trip->latitude_from, $trip->longitude_from , $driver->latitude , $driver->longitude);
                        $dist = $carObj->directDistance($trip->latitude_from, $trip->longitude_from , $driver->latitude , $driver->longitude,1.1);

                        //if($dist[2]=='OK'){
                            //if the driver in the circle
                            if($circle == 0){
                                if($dist <= $s->first_circle_radius) {
                                    $distances[$driver->id] = $dist;
                                }
                            }else{
                                if($dist <= $s->first_circle_radius*$circle*$s->other_circles_ratio)
                                {
                                    $distances[$driver->id] = $dist;
                                }
                            }
                        //}
                    }
                }
            }
        }
        $driver_id = 0; $drivers = array();
        if(!empty($distances))
        foreach ($distances as $key=>$value){
            $driver_id = $key;
            $drivers[] = $key;
            $title =($trip->is_scheduled==1)?"رحلة مجدولة جديدة":"رحلة جديدة";
            $data = [
                'trip_id'=> $trip->id,
                'notification_type'=>'New Trip',
                'is_multiple'=>$is_multiple,
                'is_driver' =>1,
                'add_features' =>1
            ];

            $body = "هناك طلب رحلة جديدة ";
            $body .= " - ";
            $body .= " مكان الانطلاق  ".$trip->location_from;
            $body .= " - ";
            $body .= " الوجهة  ".$trip->location_to;
            $body .= " - ";
            $body .= " المسافة  ".$trip_distance;
            $body .= " - ";
            $body .= " السعر  ".$trip_price;

            $notificationObj->sendNotifications($driver_id, $title, $body ,$data);
        }
        if(!empty($drivers)){
            $x = new  Notification();
            $x->saveNotification($drivers ,$title ,2, $body, 1,0);
        }
    }

    public function trip_details(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'trip_id' => 'required'
        ]);
        $user = User::find($request->user_id);
        $trip = Trip::with('carType','driver')->where('id',$request->trip_id)->first();
        if ($trip)
        {
            $carObj = new CarController();
            $driver = User::find($trip->driver_id);
            $expectedTimeToArriveDriver = 0;
            if($driver){
                if($trip->status==1 && $user->is_driver==0){

//                    $expectedTime = $carObj->googleMapDistance($driver->latitude, $driver->longitude, $trip->latitude_from, $trip->longitude_from);
                   // $carObj = new CarController();
                    //$distance = $carObj->googleMapDistance($activeTrip->latitude_from, $activeTrip->longitude_from, $activeTrip->latitude_to, $activeTrip->longitude_to);
                    $distance = $carObj->directDistance($driver->latitude, $driver->longitude, $trip->latitude_from, $trip->longitude_from,1.1);
                    //$duration = $carObj->expectedDuration($distance);

                    $expectedTimeToArriveDriver =  ($trip->is_scheduled==0)?$carObj->expectedDuration($distance):"001";
                }
                else{
                    $expectedTimeToArriveDriver = 0;
                }
                $this->saveExpectedTimeToArriveDriver($expectedTimeToArriveDriver , $driver->id ,$trip->id);
            }
            $car = Car::with('carType','carModel','brand','color')->where('driver_id',$trip->driver_id)->first();
            $invoice = Invoice::where('trip_id',$request->trip_id)->first();
            $user = User::find($trip->user_id);
            $priceForCounter = 0;

            $tripDetails = TripDetails::where('trip_id',$trip->id)->first();
            //see  if trip rated
            $rated = Rate_trip::where('trip_id',$trip->id)->first();
            if(!$invoice)
            {
               $price = round($tripDetails->expected_price ,2);
                return response()->json([
                    "success" => true,
                    "message" => "trip details",
                    'data'=> [
                        'trip'=>$trip,
                        'user'=>$user,
                        'distance'=> round($tripDetails->expected_distance,2),
                        'estimatedPrice'=>$price,
                        'expectedTimeToArriveDriver' => round($expectedTimeToArriveDriver, 2),
                        'is_invoice_calculate' => false,
                        'driver'=>$driver,
                        'car' => $car,
                        'is_rated'=>($rated)?1:0
                    ]
                ]);
            }
            else
            {
                $price = round($invoice->price ,2);
                return response()->json([
                    "success" => true,
                    "message" => "trip details",
                    'data'=> [
                        'trip'=>$trip,
                        'user'=>$user,
                        'total_distance'=> $tripDetails->expected_distance,
                        'total_price'=>$price,
                        'total_time' => round($expectedTimeToArriveDriver,2) ,
                        'is_invoice_calculate' => true,
                        'driver'=>$driver,
                        'car' => $car,
                        'is_rated'=>($rated)?1:0
                    ]
                ]);
            }
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => 'No data',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    //send active trips to driver
    //the related api get-trips-by-status
    //[driver]
    public function getActiveTrips(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
            'status' =>'required'

        ]);
        $activeTrips = array(); $totalAmount = 0; $totalAmountByDates = 0;
        $driver = User::where('id',$request->driver_id)->with('drivers_details')->first();
        if($driver->drivers_details->is_connected==1){
            //get rejected trips by driver
            $rejectedTripsIds = array();
            $rejectedTrips = Driver_rejected_trip::where('driver_id',$request->driver_id)->whereDate('created_at',Carbon::today())->pluck('trip_id')->toArray();
            if($rejectedTrips){
                $rejectedTripsIds = $rejectedTrips;
            }

            if($request->status==0){
                $d = new Driver();
                if($d->isVerified($request->driver_id)){
                    $activeTrips = Trip::with('carType','user')
                        ->Where(function($q1) use($rejectedTripsIds,$request) {
                            $q1/*->whereNotIn('id',$rejectedTripsIds)*/
                                ->where('status',$request->status);
                        })
                        ->orWhere(function($q) {
                            $q->where('status',6)
                                ->Where('is_scheduled', 1);
                        })
                        ->orderBy('id','DESC')
                        ->get();
                }
            }elseif($request->status==6){
                $activeTrips = Trip::with('carType','user')->whereNotIn('id',$rejectedTripsIds)
                    ->where('status',$request->status)
                    ->orWhere(function($q) use ($request){
                        $q->whereIn('status',[1,2,3])
                            ->where('is_scheduled',1)
                            ->where('driver_id',$request->driver_id);
                        /*->where('trip_date', '>=',date('Y-m-d H:i:s'))*/
                        /*  ->where('car_type_id',$driverCarType);*/
                    })
                    ->orderBy('id','DESC')
                    ->get();
            }else{
                $activeTrips = Trip::with('carType','user')/*->whereNotIn('id',$rejectedTripsIds)*/
                    ->where('status',$request->status)
                    ->where('driver_id',$request->driver_id)
                    /*->where('car_type_id',$driverCarType)*/
                        ->orderBy('id','DESC')
                    ->get();
            }
            if ($activeTrips || !empty($activeTrips))
            {
                //$carObj = new CarController();


                $x = new Trip();
                $tripsByDates = array();$totalAmountByDates = 0;
                if($request->start_date !='' && $request->end_date !='') {
                    $tripsByDates = $x->getTripsBetweenTwoDatesDriver($request->driver_id, $request->start_date, $request->end_date);
                    if($tripsByDates)
                    foreach ($tripsByDates as $tripsByDate)
                    {
                        //$tripDetail = TripDetails::where('trip_id',$tripsByDate->id)->first();
                        if($tripsByDate->invoice)
                        {
                            $totalAmountByDates += $tripsByDate->invoice->price;
                        }
                    }
                }
                foreach ($activeTrips as $activeTrip)
                {
                    //$distanceInMeter = $carObj->googleMapDistance($activeTrip->latitude_from, $activeTrip->longitude_from, $activeTrip->latitude_to, $activeTrip->longitude_to);
                    //$activeTrip->distance = round($distanceInMeter[0],2);
                    $tripDetail = TripDetails::where('trip_id',$activeTrip->id)->first();
                    $activeTrip->distance = round($tripDetail->expected_distance ,2);
                    if($activeTrip->invoice)
                    {
                        $activeTrip->price = round($activeTrip->invoice->price,2);
                        $totalAmount += $activeTrip->invoice->price;
                    }else{
                        $activeTrip->price = round($tripDetail->expected_price,2);
                    }
                }
                return response()->json([
                    "success" => true,
                    "message" => "Trips by status",
                    'data'=> [
                        'Trips' => $activeTrips,
                        'driver' => $driver,
                        'total_amount' =>$totalAmount,
                        'trips_by_dates'=>$tripsByDates,
                        'total_amount_by_dates' =>$totalAmountByDates
                    ]
                ]);
            }
            else {
                return response()->json([
                    "success" => false,
                    'data' => [
                        'arabic_error' => 'لا يوجد',
                        'english_error' => ' No trips!!',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
            }
        }else{
            return response()->json([
                "success" => true,
                "message" => "Trips by status",
                'data'=> [
                    'Trips' => $activeTrips,
                    'driver' => $driver,
                    'total_amount' =>$totalAmount,
                    'trips_by_dates'=>[],
                    'total_amount_by_dates' =>$totalAmountByDates
                ]
            ]);
        }

    }

    //the related api get-trips-by-status-user
    public function getTripsByStatusToUser(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
            'status' =>'required'
        ]);
        if($request->status==6){
            $trips = Trip::with('carType','driver')->whereIn('status',[$request->status,1])
                ->where('user_id',$request->user_id)
                ->where('is_scheduled',1)
                ->where('trip_date', '>=',date('Y-m-d H:i:s'))
                ->get();
        }else{
            $trips = Trip::with('carType','driver')->where('status',$request->status)->where('user_id',$request->user_id)->get();
        }

        if ($trips)
        {
            $totalAmount = 0; $totalAmountByDates = 0;
            $carObj = new CarController();
            $tripsByDates = '';
            $x = new Trip();
            if($request->start_date !='' && $request->end_date !='') {
                $tripsByDates = $x->getTripsBetweenTwoDates($request->user_id, $request->start_date, $request->end_date);
                foreach ($tripsByDates as $tripsByDate)
                {
                    if($tripsByDate->invoice)
                    {
                        $totalAmountByDates += $tripsByDate->invoice->price;
                    }
                }
            }

            foreach ($trips as $activeTrip)
            {
                //$distanceInMeter = $carObj->googleMapDistance($activeTrip->latitude_from, $activeTrip->longitude_from, $activeTrip->latitude_to, $activeTrip->longitude_to);
                $tripDetail = TripDetails::where('trip_id',$activeTrip->id)->first();
                $activeTrip->distance = round($tripDetail->expected_distance,2);

                if($activeTrip->invoice){
                    $totalAmount += $activeTrip->invoice->price;
                    $activeTrip->price = round($activeTrip->invoice->price, 2);
                }else{
                    $activeTrip->price = round($tripDetail->expected_price,2);
                }
            }
            return response()->json([
                "message" => "Trips by status to user",
                'data'=> [
                    'Trips' => $trips,
                    'total_amount' =>round($totalAmount,2),
                    'trips_by_dates'=>$tripsByDates,
                    'total_amount_by_dates' =>$totalAmountByDates
                ]
            ]);
        }
        else {
            return response()->json([
                'data' => [
                    'arabic_error' => 'لا يوجد',
                    'english_error' => ' No trips!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    //[driver]
    public function approve_trip(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
            'trip_id' => 'required',
        ]);
        //driver_lat driver_lng
        $trip = Trip::find($request->trip_id);
        if ($trip)
        {
            //trip is available and not get from other driver
            if(!$trip->driver_id){
                //if not cancelled
                if($trip->status!=5)
                {
                    $trip->driver_id = $request->driver_id;
                    $trip->driver_approve_time = Carbon::now();
                    $trip->status = 1;
                    $trip->update();

                    //add driver ip
                    $tDetails = TripDetails::where('trip_id',$request->trip_id)->first();
                    $tDetails->ip_driver = \Request::ip();
                    $tDetails->save();

                    //driver compensation
//                    $comp = new DriverCompensation();
//                    $compensationPerKilo  = $this->setting->compensation_driver_per_kilo;
//                    $comp->addItem1($request->driver_lat, $request->driver_lng, $trip, $compensationPerKilo);

                    // update driver location
                    $Coo = new Coordinate();
                    $x = $Coo->update_location($request->driver_id, $request->driver_lat, $request->driver_lng, $trip->id);

                    //send notification to user that his trip accepted
                    $notificationObj = new NotificationsController();
                    $data = [
                        'trip_id'=> $trip->id,
                        'notification_type'=>'trip',
                        'is_multiple'=>$trip->is_multiple,
                        'is_driver' => 0
                    ];
                    $body = __('message.order_approved');
                    $title = "الموافقة على الرحلة";
                    $notificationObj->sendNotifications($trip->user_id, $title, $body,$data);

                    $x = new  Notification();
                    $x->saveNotification([$trip->user_id],$title,2, $body,0,0);

                    return response()->json([
                        "message" => "successfully approved",
                        'data'=> [
                            'arabic_error'=>'',
                            'english_error'=>'',
                            'arabic_result'=>'تم تثبيت رحلة',
                            'english_result'=>'successfully approved',       ]
                    ]);
                }
                else{
                    return response()->json([
                        "message" => "--",
                        'data'=> [
                            'arabic_error'=>'--',
                            'english_error'=>'--',
                            'arabic_result'=>'',
                            'english_result'=>''   ]
                    ]);
                }
            }
            else {
                return response()->json([
                    "message" => "approved",
                    'data'=> [
                        'arabic_error'=>'أحد السائقين حصل على الرحلة',
                        'english_error'=>'Trip been got from other driver',
                        'arabic_result'=>'',
                        'english_result'=>'',       ]
                ]);
            }
        }
        else{
            return response()->json([
                'data' => [
                    'arabic_error' => 'لم يتم ',
                    'english_error' => 'Not approved!!',
                    'arabic_result' => '',
                    'english_result' => '',        ]
            ]);
        }
    }
    //[driver]
    public function reject_trip(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
            'trip_id' => 'required',
        ]);
        $rejectTrip = new Driver_rejected_trip();
        $rejectTrip->driver_id = $request->driver_id;
        $rejectTrip->trip_id = $request->trip_id;
        if ($rejectTrip->save())
        {
            return response()->json([
                "message" => "successfully rejected",
                'data'=> [
                    'arabic_error'=>'',
                    'english_error'=>'',
                    'arabic_result'=>'لم يتم قبول الرحلة',
                    'english_result'=>'Trip successfully rejected',
                ]
            ]);
        }
        else {
            return response()->json([
                'data' => [
                    'arabic_error' => 'لم يتم ',
                    'english_error' => 'Not rejected!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }
    //[driver,user]
    public function cancel_trip(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
            'trip_id' => 'required',
            'reason_id' => 'required',
        ]);
        $user = User::find($request->user_id);
        //Cancel by trip id + by user or driver
        $trip = Trip::where('id',$request->trip_id)->where(function ($query)use ($request)  {
            $query->where('user_id',$request->user_id)
                ->orWhere('driver_id',$request->user_id);
        }) ->first();
        if($trip && is_null($trip->end_date)){
            $cancel_trip = new Cancel_reason_text();
            $cancel_trip->user_id = $request->user_id;
            $cancel_trip->trip_id = $request->trip_id;
            $cancel_trip->reason_id = $request->reason_id;
            $cancel_trip->reason_text = $request->reason_text;
            $cancel_trip->is_admin = 0;

            if ($cancel_trip->save())
            {
                $trip->status = 5;
                $trip->update();
                //if user
                if($user->is_driver==0){
                    // KPI users....... sum Trip Cancelled
                    $x = new KpiUsersController();
                    $x->sumTripCancelled($request->user_id );

                    if($trip->driver_id) {
                        //send notification to driver that his trip cancelled
                        $notificationObj = new NotificationsController();
                        $data = [
                            'trip_id' => $trip->id,
                            'notification_type' => 'trip',
                            'is_multiple'=>0,
                            'is_driver' => 1
                        ];
                        $body = __('message.user_cancelled_order');
                        $notificationObj->sendNotifications($trip->driver_id, "إلغاء الرحلة", $body, $data);
                    }

                }
                else {
                    //make discount for driver
                    $x = new Balance();
                    $x->makeDiscount($request->user_id);
                    // KPI ...driver.... sum Trip Cancelled
                    $x = new KpiDriversController();
                    $x->sumTripCancelled($request->user_id );


                    //send notification to user that his trip cancelled
                    $notificationObj = new NotificationsController();
                    $data = [
                        'trip_id'=> $trip->id,
                        'notification_type'=>'trip',
                        'is_multiple'=>0,
                        'is_driver' => 0
                    ];
                    $body = __('message.driver_cancelled_order');
                    $notificationObj->sendNotifications($trip->user_id , "إلغاء الرحلة", $body,$data);
                }

                //re calculate compensation
                $driverCompensation = new DriverCompensation();
                $driverCompensation->reCalculateCompensation($trip);

                return response()->json([
                    "message" => "successfully cancelled",
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>'تم إلغاء رحلة',
                        'english_result'=>'successfully cancelled',
                    ]
                ]);
            }
            else {
                return response()->json([
                    'data' => [
                        'arabic_error' => 'لم يتم ',
                        'english_error' => 'Not cancelled!!',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
            }
        }else{
            return response()->json([
                'data' => [
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => 'No Data!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    //[driver]
    public function arrive_to_customer_location(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
            'trip_id' => 'required',
            'driver_lat'=>'required',
            'driver_lng'=>'required',
        ]);
        $trip = Trip::where('id',$request->trip_id)->where('driver_id',$request->driver_id)->first();
        if ($trip)
        {
            $trip->arrive_to_customer_time = Carbon::now();
            $trip->status = 2;
            $trip->update();




            //send notification to USER that his trip accepted
            $notificationObj = new NotificationsController();
            $data = [
                'trip_id'=> $trip->id,
                'notification_type'=>'trip',
                'is_multiple'=>$trip->is_multiple,
                'is_driver' => 0
            ];
            $body = __('message.driver_arrived_to_customer');
            $notificationObj->sendNotifications($trip->user_id, "وصل الكابتن لمكانك", $body,$data);

            // update driver location
            $Coo = new Coordinate();
            $x = $Coo->update_location($request->driver_id, $request->driver_lat, $request->driver_lng, $trip->id);

            return response()->json([
                "success" => true,
                "message" => "status updated",
                'data'=> [
                    'arabic_error'=>'',
                    'english_error'=>'',
                    'arabic_result'=>'تم الوصول إلى مكان العميل ...الرجاء بدء الرحلة عند وصوله ',
                    'english_result'=>'status updated',
                ]
            ]);
        }
        else {
            return response()->json([
                'data' => [
                    'arabic_error' => 'لم يتم ',
                    'english_error' => 'status Not updated!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }


    //[driver]
    // when driver arrive to user
    public function start_trip(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
            'trip_id' => 'required',
            'driver_lat'=>'required',
            'driver_lng'=>'required',
        ]);
        $trip = Trip::where('id',$request->trip_id)->where('driver_id',$request->driver_id)->first();

        if ($trip)
        {
            $trip->status = 3;
            $trip->start_date = Carbon::now();
            $trip->update();

            //send notification to USER that his trip accepted
            $notificationObj = new NotificationsController();
            $data = [
                'trip_id'=> $trip->id,
                'notification_type'=>'trip',
                'is_multiple'=>$trip->is_multiple,
                'is_driver' => 0
            ];
            $body = __('message.trip_started');
            $notificationObj->sendNotifications($trip->user_id, "بداية الرحلة", $body,$data);

            // update driver location
            $Coo = new Coordinate();
            $x = $Coo->update_location($request->driver_id, $request->driver_lat, $request->driver_lng, $trip->id);

            //driver compensation
            $comp = new DriverCompensation();
            $compensationPerKilo  = $this->setting->compensation_driver_per_kilo;
            $comp->addItem( $trip, $compensationPerKilo);

            return response()->json([
                "success" => true,
                "message" => "status updated",
                'data'=> [
                    'arabic_error'=>'',
                    'english_error'=>'',
                    'arabic_result'=>'تم بدء الرحلة...الرجاء حساب الفاتورة عند الوصول إلى الوجهة',
                    'english_result'=>'status updated',
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لم يتم ',
                    'english_error' => 'status Not updated!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    //[driver]
    public function end_trip(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
            'trip_id' => 'required',
            'driver_lat'=>'required',
            'driver_lng'=>'required',
        ]);

        $trip = Trip::where('id',$request->trip_id)->where('driver_id',$request->driver_id)->first();
        if ($trip)
        {
            $trip->status = 4;
            $trip->end_date = Carbon::now();
            $trip->latitude_to = $request->driver_lat;
            $trip->longitude_to = $request->driver_lng;
            $trip->update();

            $invoice = Invoice::where('trip_id',$trip->id)->first();


            if($invoice){

                //All KPIs
                $x = new KpiTripController();
                $x->doKPIs($trip, $invoice->price);

                //send notification to USER that his trip ended
                $notificationObj = new NotificationsController();
                $data = [
                    'trip_id'=> $trip->id,
                    'notification_type'=>'trip',
                    'is_multiple'=>$trip->is_multiple,
                    'is_driver' => 0
                ];
                $body = __('message.trip_ended');
                $notificationObj->sendNotifications($trip->user_id, "نهاية الرحلة", $body,$data);

                //send good bye notification to USER
                $data = [
                    'trip_id'=> $trip->id,
                    'notification_type'=>'trip',
                    'is_multiple'=>$trip->is_multiple,
                    'is_driver' => 0
                ];
                $set = $this->setting;
                $body = $set->bye_message_arabic;
                $notificationObj->sendNotifications($trip->user_id, "شكراً لكم", $body,$data);

                // update driver location
                $Coo = new Coordinate();
                $x = $Coo->update_location($request->driver_id, $request->driver_lat, $request->driver_lng, $trip->id);

                return response()->json([
                    "success" => true,
                    "message" => "status updated",
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>'تم إنهاء الرحلة...شكراً لك ',
                        'english_result'=>'status updated',
                    ]
                ]);
            }else{
                return response()->json([
                    "success" => false,
                    'data' => [
                        'arabic_error' => 'لم يتم ',
                        'english_error' => 'status Not updated!!',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
            }
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لم يتم ',
                    'english_error' => 'status Not updated!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function calculate_invoice(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
            'trip_id'=>'required'
        ]);
        $trip = Trip::with('carType')->where('id',$request->trip_id)->where('driver_id',$request->driver_id)->first();

        //$s = new Setting();
        $setting = $this->setting;
        if($trip)
        {
            return response()->json(
                [
                    'message'=>' Trip Invoice',
                    'data'=> [

                        'c'=>$trip,
                        's'=>$setting,
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>' تم تقييم الرحلة',
                        'english_result'=>'Trip rate done',
                    ]
                ]
            );
        }
    }


    //return is_connected
    //return items for homepage
    public function getActiveTripToDriver(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
        ]);
        //echo date('Y-m-d H:i:s');
        $activeTrip = Trip::with('carType','driver')->where('driver_id',$request->driver_id)
            ->whereIn('status',[1,2,3])
            ->where('is_scheduled','!=',1)
            ->orWhere(function($q) use ($request){
                $q->where('trip_date', '<=', date('Y-m-d H:i:s'))
                    ->where('driver_id',$request->driver_id)
                    ->whereIn('status',[1,2,3])
                    ->Where('is_scheduled', 1);
            })
            ->first();
        $nextScheduledTrip = 0;
        $price = 0;
        //connected
        $user = User::find($request->driver_id);
        $driver = Driver::where('user_id',$user->id)->first();
        //see if he has alert not seen and send older one
        $driverAlert = DriverAlert::where('driver_id',$request->driver_id)->where('seen',0)->first();
        $daily_kpi = Daily_kpis::where('driver_id',$request->driver_id)->whereDate('updated_at',Carbon::today())->first();

        //get balance
        $today = Carbon::now();
        //$x = Sum_money::where('driver_id',$request->driver_id)->where('work_day', $today->toDateString())->first();
        $sumMoney = new Sum_money();
        $x = $sumMoney->driverBalance($request->driver_id);
        $balance = 0;$numOfRenewRequests = 1;
        if($x){
            $balance = $x->balance;
            $x = new Setting();
            $min_amount_to_stop_driver = $x->getSetting()->min_amount_to_stop_driver;
            $isBalanceFinished = $min_amount_to_stop_driver + $balance;
            //num of renew balance
            $numOfRenewRequests = $x->num_of_renew_requests;
        }else{
            $isBalanceFinished = 0;
        }

        if ($activeTrip)
        {
            //$distance = $carObj->googleMapDistance($activeTrip->latitude_from, $activeTrip->longitude_from, $activeTrip->latitude_to, $activeTrip->longitude_to);
            $carObj = new CarController();
            //$distance = $carObj->googleMapDistance($activeTrip->latitude_from, $activeTrip->longitude_from, $activeTrip->latitude_to, $activeTrip->longitude_to);
            $distance = $carObj->directDistance($activeTrip->latitude_from, $activeTrip->longitude_from, $activeTrip->latitude_to, $activeTrip->longitude_to,1.1);
            $duration = $carObj->expectedDuration($distance);

            $price = round($this->estimatePrice($distance ,$duration, $activeTrip),2);

            return response()->json([
                "success" => true,
                "message" => "Active Trips",
                'data'=> [
                    'Trip' => $activeTrip,
                    'estimatedPrice'=>$price,
                    'alert' => ($driverAlert)?$driverAlert:'',
                    'distance'=>$distance,
                    'is_connected'=>$driver->is_connected,
                    'daily_kpi'=>$daily_kpi,
                    'balance' => $balance,
                    'is_balance_finished' => ($isBalanceFinished>=0)?0:1,
                    'num_of_renew_requests' =>$numOfRenewRequests
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'alert' => ($driverAlert)?$driverAlert:'',
                    'arabic_error' => 'لا يوجد',
                    'english_error' => ' No trips!!',
                    'arabic_result' => '',
                    'english_result' => '',
                    'is_connected'=>$driver->is_connected,
                    'daily_kpi'=>$daily_kpi,
                    'balance' => $balance,
                    'num_of_renew_requests' =>$numOfRenewRequests
                ]
            ]);
        }
    }
    //active trip + trip with status 0
    public function getActiveTripToUser(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
        ]);

        //get nearest driver
        $X = new User();
        $drivers = $X->getDrivers();
        $user = User::find($request->user_id);
        $nearests = array();
        $y = new CarController();
        foreach ($drivers as $nearestDriver){
            if( $nearestDriver->latitude != 0 && is_numeric($nearestDriver->latitude) && $nearestDriver->longitude != 0 && is_numeric($nearestDriver->longitude)){
                //$x = $y->googleMapDistance($user->latitude , $user->longitude , $nearestDriver->latitude , $nearestDriver->longitude);
                $dis = $y->directDistance($user->latitude, $user->longitude , $nearestDriver->latitude , $nearestDriver->longitude,1.1);
               //radius smaller than 5 kilometers
//                if($x[2]=='OK' && (float)$x[0]<=5)
//                {
                    $nearest =  new stdClass();
                    $nearest->id = $nearestDriver->id;
                    $nearest->latitude = $nearestDriver->latitude;
                    $nearest->longitude = $nearestDriver->longitude;
                    $nearest->distance = (float)$dis;
                    //$nearests[$nearestDriver->id] = (float)$x[0];
                    array_push($nearests,$nearest);
//                }
            }
        }
        usort($nearests , function($a, $b) {return strcmp($a->id, $b->id);});
        $nearests = array_slice($nearests, 0, 5);
        //echo Carbon\Carbon::now();
        $user_id = $request->user_id;
        $activeTrip = Trip::with('carType','driver')->where('user_id',$user_id)
            ->where('status','!=',5)->where('status','!=',4)
            ->where('is_scheduled','!=',1)

            ->orWhere(function($q) use($user_id){
                //where trip_date in the past
                $q->where('trip_date', '<=', date('Y-m-d H:i:s'))
                    ->where('user_id',$user_id)
                    ->whereIn('status',[6,1,2,3])
                    ->Where('is_scheduled', 1);
            })
            ->first();
        //check offers
//        $o = new Offers();
//        $off = $o->checkOffers($request->user_id);
//        $checkOffers =  ($off)?$off:false;

        if ($activeTrip)
        {
            $carObj = new CarController();
            //$distance = $carObj->googleMapDistance($activeTrip->latitude_from, $activeTrip->longitude_from, $activeTrip->latitude_to, $activeTrip->longitude_to);
            $distance = $carObj->directDistance($activeTrip->latitude_from, $activeTrip->longitude_from, $activeTrip->latitude_to, $activeTrip->longitude_to,1.1);
            $duration = $carObj->expectedDuration($distance);
            $price = round($this->estimatePrice($distance ,$duration , $activeTrip),2);

            return response()->json([
                "success" => true,
                "message" => "Active Trip",
                'data'=> [
                    'Trip' => $activeTrip,
                    'estimatedPrice'=>$price,
                    'nearest_driver'=>$nearests,
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لا يوجد',
                    'english_error' => ' No trips!!',
                    'arabic_result' => '',
                    'english_result' => '',
                    'nearest_driver'=>$nearests,
                ]
            ]);
        }
    }

    public function sharedTrips(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
        ]);

       // $sharedTrips = SharedTrip::with('trip')->where('receiver_share',$request->user_id)->get();
//        $sharedTrips = Trip::where('id',$request->trip_id)->where(function ($query)use ($request)  {
//            $query->where('user_id',$request->user_id)
//                ->orWhere('driver_id',$request->user_id);
//        }) ->first();
        $sharedTrips = SharedTrip::where('receiver_share',$request->user_id)->whereHas('trip',function($subQ) {
            $subQ->where('status','!=',5)->where('status','!=',4);
        })->with('trip')->get();
        if ($sharedTrips)
        {
            return response()->json([
                "success" => true,
                'data' => [
                    'arabic_error' => '',
                    'english_error' => '',
                    'sharedTrips' => $sharedTrips
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لا يوجد',
                    'english_error' => ' No trips!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function scheduledTrips(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
        ]);

        $scheduledTrips = Trip::where('user_id',$request->user_id)->where('is_scheduled',1)
            ->where('trip_date', '<=',date('Y-m-d H:i:s'))->where('status','!=',5)->get();

        if ($scheduledTrips)
        {
            return response()->json([
                "success" => true,
                'data' => [
                    'arabic_error' => '',
                    'english_error' => '',
                    'scheduledTrips' => $scheduledTrips
                ]
            ]);
        }

        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لا يوجد',
                    'english_error' => ' No trips!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function preEstimatePrice($distance, $carTypePrice)
    {
        //$s = new Setting();
        $openPrice = $this->setting->price_open;
       // $openPrice = $set->price_open;
        $long_distance_start_after = floatval($this->setting->long_distance_start_after);
        //long distance regions
        if($distance > $long_distance_start_after){
            $r = new FarwayRegion();
            $extraDistance = $distance - $long_distance_start_after;
            $extraPrice = $r->getRange($extraDistance);
            return $this->roundUp(($distance *  $carTypePrice) + floatval($openPrice) + ($extraDistance * $extraPrice));
        }else{
            return $this->roundUp(($distance *  $carTypePrice) + floatval($openPrice));
        }

    }

    public function estimatePrice($distance, $expectedDuration, $trip)
    {
        //$s = new Setting();
        $set = $this->setting;
        $openPrice = $set->price_open;
        $long_distance_start_after = $set->long_distance_start_after;
        //$startTime = Carbon::parse($trip->start_date);
        $endTime = Carbon::now();
        if(!is_null($trip->start_date)){
            $realDuration = ceil($trip->getDifferenceOfTimeInMinutes($endTime,$trip->start_date));
        }else{
            $realDuration = ceil($trip->getDifferenceOfTimeInMinutes($endTime,$trip->arrive_to_customer_time));
        }

        //$realDuration = $endTime->diffInMinutes($startTime);
        $ex = $realDuration - $expectedDuration;
        $extraDuration = (is_numeric($ex) && $ex > 0) ? $ex:0;
        //long distance regions
        if($distance > $long_distance_start_after){
            $extraDistance = $distance - $long_distance_start_after;
            $r = new FarwayRegion();
            $extraPriceForFarRegion = $r->getRange($extraDistance);

            return $this->roundUp(($distance *  $trip->carType->price)+($extraDuration * $trip->carType->minute_price) + floatval($openPrice) + ($extraDistance * $extraPriceForFarRegion));
        }else{
            return $this->roundUp(($distance *  $trip->carType->price)+($extraDuration * $trip->carType->minute_price) + floatval($openPrice));
        }
    }

    public function wholePriceWithExtraDuration($wholePrice,$expectedDuration,$trip)
    {
        //$s = new Setting();
        $set = $this->setting;;
        $openPrice = $set->price_open;
        $startTime = Carbon::parse($trip->start_date);
        $endTime = Carbon::now();

        $endTime->diffInMinutes($startTime);
        $realDuration = $endTime->diffInMinutes($startTime);
        $ex = $realDuration - $expectedDuration;
        $extraDuration = (is_numeric($ex) && $ex > 0) ? $ex:0;
        return $this->roundUp($wholePrice + ($extraDuration * $trip->carType->minute_price) );
    }

    public function PricePerKilometer($distance, $carTypePrice)
    {
        return floatval($distance *  $carTypePrice);
    }

    public function saveExpectedTimeToArriveDriver($expectedTimeToArriveDriver , $driver_id ,$trip_id){

        $x = new TimeToArriveDriver();
        $x->driver_id = $driver_id;
        $x->trip_id = $trip_id;
        $x->time_arrive = $expectedTimeToArriveDriver;
        $x->save();

        //calculate avg time arrive in kpi table
        $driver_kpi = new KpiDriversController();
        $driver_kpi->avgArriveTime($driver_id);
    }


    public function tripDetailswithCoordinates(Request $request)
    {
        $request->validate([
            'trip_id' => 'required'
        ]);
        $trip = Trip::with('carType','driver')->where('id',$request->trip_id)->first();
        if ($trip)
        {
            $carObj = new CarController();
            $expectedTimeToArriveDriver = 0;
            if($trip->driver){
                if($trip->status==1 ){
                    $distance = $carObj->openStreetMapDistance($trip->driver->latitude, $trip->driver->longitude, $trip->latitude_from, $trip->longitude_from);

                    $expectedTimeToArriveDriver =  ($trip->is_scheduled==0)?$carObj->expectedDuration($distance):"001";
                }
                else{
                    $expectedTimeToArriveDriver = 0;
                }

                return response()->json([
                    "success" => true,
                    'data' => [
                        'arabic_error' => '',
                        'english_error' => '',
                        'expectedTimeToArriveDriver' => $expectedTimeToArriveDriver,
                        'status' =>$trip->status,
                        'driver_lat' => $trip->driver->latitude,
                        'driver_lng' => $trip->driver->longitude,
                    ]
                ]);
            }
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => 'No data',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function test()
    {
        $Coo = new Coordinate();
        $x = $Coo->update_location(192, 33.656565, 36.25545, 0);
        echo $x;
    }






}
