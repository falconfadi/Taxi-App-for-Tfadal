<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Invoice;
use App\Models\MultiTrip;
use App\Models\Notification;
use App\Models\Rate_trip;
use App\Models\Setting;
use App\Models\Trip;
use App\Models\TripDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
class MultiTripController extends Controller
{

    //[user]
    public function add_trip_multi(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
        ]);
        $trip = new Trip();
        $trip->user_id = $request->user_id;
        $trip->latitude_from = $request->latitude_from;
        $trip->longitude_from = $request->longitude_from;
        $trip->latitude_to = $request->latitude_to;
        $trip->longitude_to = $request->longitude_to;
        $trip->location_from = $request->location_from;
        $trip->location_to = $request->location_to;
        $trip->car_type_id = ($request->car_type_id)?$request->car_type_id:'13';
        $trip->trip_date = Carbon::now();
        //$trip->google_cost = $request->google_cost;
        $trip->silence_trip = ($request->silence_trip)?$request->silence_trip:0;
        $trip->enable_discount = $request->enable_discount;
        $trip->note = ($request->note)?$request->note:'';
        $trip->is_multiple = 1;
        $trip->num_of_trips = $request->num_of_trips;
        $trip->trip_number = 1;


        //get last serial number and generate one
        $t = new Trip();
        $trip->serial_num = $t->getLastSerialNumber();

        //if user already has an active trip
        //$haveTrip = Trip::whereIn('status',[1,2,3])->where('user_id',$request->user_id)->first();
        $haveTrip = Trip::whereIn('status',[0,1,2,3])->where('is_scheduled',0)->where('user_id',$request->user_id)->first();
        if($haveTrip) {
            return response()->json([
                "success" => false,
                "message" => "successfully added",
                'data' => [
                    'arabic_error' => 'عذراً لديك رحلة حالية',
                    'english_error' => 'You already have active trip',
                    'arabic_result' => '',
                    'english_result' => '',
                    'trip_id' => $trip->id
                ]
            ]);
        }

        if ($trip->save())
        {
            //save trip details
            $tDetails = new TripDetails();
//            $tDetails->expected_distance = $request->expected_distance;
//            $tDetails->expected_duration = $request->expected_duration;
            $C = new CarController();
            $tDetails->expected_distance = $request->expected_distance;//$C->directDistance($trip->latitude_from, $trip->longitude_from, $trip->latitude_to, $trip->longitude_to, 1.2);
            $tDetails->expected_duration = $C->expectedDuration($tDetails->expected_distance );
            if($request->expected_price && !is_null($request->expected_price) && $request->expected_price !=0 ){
                $tDetails->expected_price = $request->expected_price;
            }else{
                $tripObj = new TripController();
                $tDetails->expected_price = $this->roundUp($tripObj->preEstimatePrice($request->expected_distance , (int)$trip->carType->price));
            }
            $tDetails->trip_id = $trip->id;
            $tDetails->ip_user = \Request::ip();
            $tDetails->save();

            $stop_locations = $request->stop_locations;
            for($move_num=2;$move_num<=5;$move_num++){

                $multi = new MultiTrip();
                $latitude_item = "latitude_stop_".$move_num;
                $longitude_item = "longitude_stop_".$move_num;
                $location_item = "location_stop_".$move_num;
                if($stop_locations[$latitude_item]!= 0){
                    $multi->trip_id = $trip->id;
                    $multi->latitude_stop = $stop_locations[$latitude_item];
                    $multi->longitude_stop = $stop_locations[$longitude_item];
                    $multi->location_stop = $stop_locations[$location_item];
                    $multi->order = $move_num;
                    $multi->save();
                }
            }

            //send notification to drivers
            $tripObg = new TripController();
            $tripObg->sendNotificationsToDrivers($trip->id, 0, $request->trip_price, $request->trip_distance,1);
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

    public function trip_details_multi(Request $request)
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
                if($trip->status==1 && $user->is_driver==0) {
                    //$expectedTime = $carObj->googleMapDistance($driver->latitude, $driver->longitude, $trip->latitude_from, $trip->longitude_from);
                    $distance = $carObj->directDistance($driver->latitude, $driver->longitude, $trip->latitude_from, $trip->longitude_from,1.1);
                    //$duration = $carObj->expectedDuration($distance);
                    $expectedTimeToArriveDriver =  $carObj->expectedDuration($distance);
                    //$expectedTimeToArriveDriver = $expectedTime[1];
                }else{
                    $expectedTimeToArriveDriver=0;
                }
                $tripObj = new TripController();
                $tripObj->saveExpectedTimeToArriveDriver($expectedTimeToArriveDriver , $driver->id ,$trip->id);
            }
            //get stop locations array
            $stop_locations = MultiTrip::where('trip_id',$trip->id)->get();

            //get stop locations array as parameters
            $newTripOrder = $trip->trip_number+1;
            //
            $multi = MultiTrip::where('trip_id',$request->trip_id)->where('order',$trip->trip_number)->first();
            $multiPrev = MultiTrip::where('trip_id',$trip->id)->where('order',$trip->trip_number-1)->first();
            $latitude_from = '';
            $longitude_from = '';
            $latitude_to = '';
            $longitude_to = '';
            //$newTripOrder = $trip->trip_number+1;
            if($trip->trip_number==1){
                $latitude_from = $trip->latitude_from;
                $longitude_from = $trip->longitude_from;
                $latitude_to = $trip->latitude_to;
                $longitude_to = $trip->longitude_to;
            }elseif($trip->trip_number==2){
               // if($multiPrevious && $multi){
                    $latitude_from =  $trip->latitude_to;//$multiPrevious->latitude_stop;
                    $longitude_from = $trip->longitude_to;//$multiPrevious->longitude_stop;
                    $latitude_to = $multi->latitude_stop;
                    $longitude_to = $multi->longitude_stop;
                }else{
                    $latitude_from = $multiPrev->latitude_stop;
                    $longitude_from = $multiPrev->longitude_stop;
                    $latitude_to = $multi->latitude_stop;
                    $longitude_to = $multi->longitude_stop;
                }

            $car = Car::with('carType','carModel','brand','color')->where('driver_id',$trip->driver_id)->first();
            $invoice = Invoice::where('trip_id',$request->trip_id)->first();
            $user = User::find($trip->user_id);
            $distance = $this->expectedDistanceMulti($trip->id);
            $tripDetails = TripDetails::where('trip_id',$trip->id)->first();
            //see  if trip rated
            $rated = Rate_trip::where('trip_id',$trip->id)->first();
            if(!$invoice)
            {
                $x = new TripController();
               // $price = round($x->estimatePrice($distance[0] ,$distance[1], $trip),2);
                $price = round($tripDetails->expected_price ,2);
                return response()->json([
                    "success" => true,
                    "message" => "trip details",
                    'data'=> [
                        'trip'=>$trip,
                        'user'=>$user,
                        'distance'=> round($distance[0],2),
                        'estimatedPrice'=>$price,
                        'expectedTimeToArriveDriver' => round($expectedTimeToArriveDriver, 2),
                        'is_invoice_calculate' => false,
                        'driver'=>$driver,
                        'car' => $car,
                        'is_rated'=>($rated)?1:0,
                        'stop_locations' => $stop_locations,
                        'latitude_from'=>$latitude_from,
                        'longitude_from'=>$longitude_from,
                        'latitude_to'=>$latitude_to,
                        'longitude_to'=>$longitude_to,
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
                        'total_distance'=> $distance[0],
                        'total_price'=>$price,
                        'total_time' => round($expectedTimeToArriveDriver, 2),
                        'is_invoice_calculate' => true,
                        'driver'=>$driver,
                        'car' => $car,
                        'is_rated'=>($rated)?1:0,
                        'stop_locations' => $stop_locations,
                        'latitude_from'=>$latitude_from,
                        'longitude_from'=>$longitude_from,
                        'latitude_to'=>$latitude_to,
                        'longitude_to'=>$longitude_to,
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
        //send notification to all drivers
    }

    public function update_trip_multi(Request $request)
    {
        $request->validate([
            'trip_id' =>'required'
        ]);
        $trip = Trip::find($request->trip_id);

        if ($trip)
        {
            $newTripOrder = $trip->trip_number + 1;
            $multi = MultiTrip::where('trip_id',$request->trip_id)->where('order',$newTripOrder)->first();
            $trip->trip_number = $newTripOrder;
            $trip->update();
            if($multi){
                if($newTripOrder==2){
                    $latitude_from = $trip->latitude_to;
                    $longitude_from = $trip->longitude_to;
                    $latitude_to = $multi->latitude_stop;
                    $longitude_to = $multi->longitude_stop;
                }else{
                    $multiPrevious = MultiTrip::where('trip_id',$request->trip_id)->where('order',$trip->trip_number)->first();
                    $latitude_from = $multiPrevious->latitude_stop;
                    $longitude_from = $multiPrevious->longitude_stop;
                    $latitude_to = $multi->latitude_stop;
                    $longitude_to = $multi->longitude_stop;
                }

                $notificationObj = new NotificationsController();
                $data = [
                    'trip_id'=> $trip->id,
                    'notification_type'=>'trip',
                    'is_multiple'=> $trip->is_multiple,
                    'is_driver' => 0
                ];
                //$user = User::find($trip->user_id);
                $body = "  تم الوصول للوجهة:  ".$multi->location_stop;
                $title = "Trip";
                $notificationObj->sendNotifications($trip->user_id, $title, $body ,$data);

                $x = new  Notification();
                $x->saveNotification([$trip->user_id],$title,2, $body, 0,0);

                return response()->json([
                    "success" => true,
                    "message" => "تم الوصول للوجهة التالية",
                    'data'=> [
                        'latitude_from'=>$latitude_from,
                        'longitude_from'=>$longitude_from,
                        'latitude_to'=>$latitude_to,
                        'longitude_to'=>$longitude_to,
                        'trip_number'=>$newTripOrder,
                    ]
                ]);
            }
            else{
                return response()->json([
                    "success" => false,
                    'data' => [
                        'arabic_error' => 'انتهت',
                        'english_error' => 'Not data!!',
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
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => 'Not data!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function expectedDistanceMulti($trip_id){
        $wholeDistance = 0;$wholeDuration = 0;
        $trip = Trip::find($trip_id);
        $multiTrip = MultiTrip::where('trip_id',$trip_id)->get();
        $carObj = new CarController();
        //$expectedDist = $carObj->googleMapDistance( $trip->latitude_from, $trip->longitude_from,$trip->latitude_to, $trip->longitude_to);
        $expectedDist = $carObj->openStreetMapDistance($trip->latitude_from, $trip->longitude_from,$trip->latitude_to, $trip->longitude_to);
        $duration =  $carObj->expectedDuration($expectedDist);
        $wholeDistance += $expectedDist;
        $wholeDuration += $duration;
        //echo $expectedDist[0];echo '<br>';
        $latitude_from = $trip->latitude_to;
        $longitude_from = $trip->longitude_to;
        if(count($multiTrip)>0)
        {
            $expectedDist = $duration = 0;
            foreach ($multiTrip as $tripItem) {
                $latitude_to = $tripItem->latitude_stop;
                $longitude_to = $tripItem->longitude_stop;

                $expectedDist = $carObj->directDistance($latitude_from, $longitude_from,$latitude_to, $longitude_to,1.2);
                //$duration = $carObj->expectedDuration($distance);

                $duration =  $carObj->expectedDuration($expectedDist);

                $wholeDistance += $expectedDist;
                $wholeDuration += $duration;
                $latitude_from = $latitude_to;
                $longitude_from = $longitude_to;
            }
        }
        $distanceDuration = array();
        $distanceDuration[0] = $wholeDistance;
        $distanceDuration[1] = $wholeDuration;
        return $distanceDuration;
    }

    public function test(){
        $x = $this->expectedTimeAndDistance(31);
        echo $x;
    }
}
