<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PointLocationController;
use App\Models\Area;
use App\Models\Border;
use App\Models\Car;
use App\Models\Car_model;
use App\Models\Car_type;
use App\Models\Coordinate;
use App\Models\Driver;
use App\Models\DriverCompensation;
use App\Models\Setting;
use App\Models\Trip;
use App\Models\User;
use DateInterval;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;

class CarController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Damascus');
    }
    public function all_cars()
    {
        $cars = Car::with('driver','car_type')->get();

        return response()->json(
            [
                'message'=>'cars',
                'data'=> [
                    'cars'=> $cars
                ]
            ]
        );
    }

    // get cars and prices according to distance
    public function get_cars_type_with_value(Request $request)
    {
        $request->validate([
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'destiniation_lat' => 'required',
            'destiniation_lng' => 'required',
        ]);

        //check if given location is in available area
        if(!$this->checkAvailableArea($request->destiniation_lat, $request->destiniation_lng) || !$this->checkAvailableArea($request->pickup_lat, $request->pickup_lng))
        {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => __('message.not_avialable_area'),
                    'english_error' => 'Not Available area',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
        else{
            //distance between start and target
            //time between drivers and start
            $distance = 0;
//            $distance = floatval($this->openStreetMapDistance($request->pickup_lat, $request->pickup_lng, $request->destiniation_lat, $request->destiniation_lng));
//            if(!$distance){
//                $distance =  floatval($this->directDistance($request->pickup_lat, $request->pickup_lng, $request->destiniation_lat, $request->destiniation_lng, 1.4));
//            }


            if($request->distance && is_numeric($request->distance) && $request->distance>0){
                $distance = $request->distance;
            }else{
                $distance = $this->expectedDitance($request->pickup_lat, $request->pickup_lng, $request->destiniation_lat, $request->destiniation_lng);
            }
          //  $distance = $this->expectedDitance($request->pickup_lat, $request->pickup_lng, $request->destiniation_lat, $request->destiniation_lng);
            $expectedDuration = floatval($this->expectedDuration($distance));

            $Car_type = new Car_type();
            $carTypes = $Car_type->getExistCarTypes($request->not_furniture);
            $carsValues = array();
            $distance = ($distance > 1)? $distance:1;
            if($distance>=10 && $distance<=20) {
                $distance *= 1.05;
            }
            foreach ($carTypes as $carType){

                $tripObj = new TripController();



                $estimatedPrice = $tripObj->preEstimatePrice($distance , (int)$carType->price);
                $carsValue =  new stdClass();
                $carsValue->estimated_price = $estimatedPrice;
                $carsValue->image = $carType->image;
                $carsValue->passenger_number = ($carType->passenger_number)?$carType->passenger_number:4;
                $carsValue->english_title = $carType->name ;
                $carsValue->arabic_title = $carType->name_ar ;
                $carsValue->car_type_id = $carType->id;
                array_push($carsValues , $carsValue);
            }

            $dr = new User();
            $availableDrivers = $dr->getAvailableDrivers();
            return response()->json(
                [
                    'message'=>'Cars values',
                    'data'=> [
                        'carTypes'=>$carsValues,
                        'distance'=> ceil($distance),
                        'expected_duration'=>ceil($expectedDuration),
                        'time_to_arrive_driver' => ''/*ceil($this->timeToArriveDriver($request->pickup_lat, $request->pickup_lng))*/,
                        'num_of_available_drivers' => (count($availableDrivers)>0)?count($availableDrivers):0,
                        'is_have_point_discount' =>false,/*$is_have_point_discount*/
                    ]
                ]
            );
        }
    }

    // get cars and prices according to distance
    public function get_cars_type_with_value_multi(Request $request)
    {
        $request->validate([
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'destiniation_lat' => 'required',
            'destiniation_lng' => 'required',
        ]);

        //check if given point is in available area
        if(!$this->checkAvailableArea($request->destiniation_lat, $request->destiniation_lng) || !$this->checkAvailableArea($request->pickup_lat, $request->pickup_lng))
        {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'منطقة غير متاحة',
                    'english_error' => 'Not Available area',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
        else{
            //distance between start and target
            //time between drivers and start
            $distance = 0;
            $distanceArr = array();
            $firstDistanceValue = floatval($this->openStreetMapDistance($request->pickup_lat, $request->pickup_lng, $request->destiniation_lat, $request->destiniation_lng));
            if(!$firstDistanceValue){
                $firstDistanceValue =  floatval($this->directDistance($request->pickup_lat, $request->pickup_lng, $request->destiniation_lat, $request->destiniation_lng, 1.4));
            }
           // $firstDistanceValue =  $this->directDistance($request->pickup_lat, $request->pickup_lng, $request->destiniation_lat, $request->destiniation_lng,1.4);
            $distanceArr[] =  $firstDistanceValue;
            //$expectedDuration =  floatval($this->expectedDuration($firstDistanceValue));
            $move_num = 2;
            $latitude_item = "latitude_stop_".$move_num;
            $longitude_item = "longitude_stop_".$move_num;
            $pickup_lat = $request->destiniation_lat;
            $pickup_lng = $request->destiniation_lng;
            $stop_locations = $request->stop_locations;
            while($move_num<=5 && $stop_locations[$latitude_item] &&  $stop_locations[$latitude_item]!= 0  ){
                if($move_num==6){
                    break;
                }else{
                    $destiniation_lat = $stop_locations[$latitude_item];
                    $destiniation_lng = $stop_locations[$longitude_item];

                    $distanceArr[] =  floatval($this->directDistance($pickup_lat, $pickup_lng, $destiniation_lat, $destiniation_lng,1.4));
                    //$distanceArr[] =  floatval($distanceAndDuration[0]);
                    $move_num++;
                    $latitude_item = "latitude_stop_".$move_num;
                    $longitude_item = "longitude_stop_".$move_num;
                    $pickup_lat = $destiniation_lat;
                    $pickup_lng = $destiniation_lng;
                }
            }
            $all_distances = array_sum($distanceArr);
            // $carTypes = Car_type::with('cars');
            $Car_type = new Car_type();
            $carTypes = $Car_type->getExistCarTypesWithoutFurniture();
            $carsValues = array();
            //$carsValue =  new stdClass();

            foreach ($carTypes as $carType){
                $tripObj = new TripController();
                $estimatedPrice = $tripObj->preEstimatePrice($all_distances , $carType->price);
                $carsValue =  new stdClass();
                $carsValue->estimated_price = ceil($estimatedPrice);
                $carsValue->image = $carType->image;
                $carsValue->passenger_number = 4;
                $carsValue->english_title = $carType->name ;
                $carsValue->arabic_title = $carType->name_ar ;
                $carsValue->car_type_id = $carType->id;
                array_push($carsValues , $carsValue);
            }
            $expectedDuration =  floatval($this->expectedDuration($all_distances));
            //$c = new PointsController();
            $is_have_point_discount = false;
            //$is_have_point_discount = $c->checkPointsAvailabilty($request->user_id);
            $dr = new User();
            $availableDrivers = $dr->getAvailableDrivers();
            return response()->json(
                [
                    'message'=>'Cars values',
                    'data'=> [
                        'carTypes'=>$carsValues,
                        'distance'=>ceil($all_distances),
                        'expected_duration' =>ceil($expectedDuration),
                        'time_to_arrive_driver' => ''/*ceil($this->timeToArriveDriver($request->pickup_lat, $request->pickup_lng))*/,
                        'num_of_available_drivers' => (count($availableDrivers)>0)?count($availableDrivers):0,
                        'is_have_point_discount' =>$is_have_point_discount
                    ]
                ]
            );
        }
    }


    public function timeToArriveDriver($lat , $long)
    {
        $X = new User();
        $drivers = $X->getDrivers();
        $distances = array();
        foreach ($drivers as $nearestDriver){
            if( $nearestDriver->latitude != 0 && is_numeric($nearestDriver->latitude) && $nearestDriver->longitude != 0 && is_numeric($nearestDriver->longitude)){
                $x = $this->googleMapDistance($lat , $long , $nearestDriver->latitude , $nearestDriver->longitude);
                //echo $nearestDriver->id;echo "\n";
                if($x[2]=='OK')
                array_push($distances , (float)$x[0]) ;
            }
        }

        sort($distances);
        //take first 4 from drivers distance
        $distance = array_slice($distances, 0, 4);
        //get avg
        $distance = array_filter($distance);

        if(count($distance)!=0)
        $average = round(array_sum($distance)/count($distance),2);
        else $average = 0 ;
        return $average;
    }

    function isValidLongitude($longitude){
        $length =  strlen(substr(strrchr($longitude, "."), 1));
        if (preg_match("/^(\+|-)?((\d((\.)|\.\d{1,6})?)|(0*?\d\d((\.)|\.\d{1,6})?)|(0*?1[0-7]\d((\.)|\.\d{1,6})?)|(0*?180((\.)|\.0{1,6})?))$/", $longitude) &&  $length>=6) {
            return true;
        } else {
            return false;
        }
    }
    function isValidLatitude($lat){
        $length =  strlen(substr(strrchr($lat, "."), 1));
        if (preg_match("/^(\+|-)?((\d((\.)|\.\d{1,6})?)|(0*?\d\d((\.)|\.\d{1,6})?)|(0*?1[0-7]\d((\.)|\.\d{1,6})?)|(0*?180((\.)|\.0{1,6})?))$/", $lat) &&  $length>=6) {
            return true;
        } else {
            return false;
        }
    }

    function googleMapDistance($latitudeFrom=0, $longitudeFrom=0, $latitudeTo=0, $longitudeTo=0)
    {
        $latitudeFrom = floatval(trim($latitudeFrom));
        $longitudeFrom = floatval(trim($longitudeFrom));
        $latitudeTo = floatval(trim($latitudeTo));
        $longitudeTo = floatval(trim($longitudeTo));
        $from_latlong = $latitudeFrom.','.$longitudeFrom;
        $to_latlong = $latitudeTo.','.$longitudeTo;
        $googleApi  = env('GOOGLE_MAPS_API_KEY');

        $distance_data = file_get_contents(
            'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins='.$from_latlong.'&destinations='.$to_latlong.'&key='.$googleApi
        );
       $distanceDurtion = array();
       $distance_arr = json_decode($distance_data);

        foreach ( $distance_arr->rows[0] as $key => $element )  {
            //var_dump($element);
            //echo "\n";echo "--------";echo "\n";
            $distance = $element[0]->distance->text;
            //echo $element[0]->distance->text; echo "\n";
            $duration = $element[0]->duration->text;
            $status = $element[0]->status;
            // The matching ID
            //$id = $dests[$key];
            //echo $status;
            $distance = preg_replace("/[^0-9.]/", "",  $distance);
            $duration = preg_replace("/[^0-9.]/", "",  $duration);

            // to kilometers
            $distance = $distance * 1.609344;

            $distance = number_format($distance, 2, '.', '');
            $duration = number_format($duration, 1, '.', '');
            $distanceDurtion[0] = $distance ; $distanceDurtion[1] = $duration; $distanceDurtion[2] = $status;
            //echo $status."--";
        }
        return $distanceDurtion;
    }

    function openStreetMapDistance($latitudeFrom=0, $longitudeFrom=0, $latitudeTo=0, $longitudeTo=0)
    {
        $latitudeFrom = floatval(trim($latitudeFrom));
        $longitudeFrom = floatval(trim($longitudeFrom));
        $latitudeTo = floatval(trim($latitudeTo));
        $longitudeTo = floatval(trim($longitudeTo));
        $from_latlong = $longitudeFrom.','.$latitudeFrom;
        $to_latlong = $longitudeTo.','.$latitudeTo;
        //$googleApi  = env('GOOGLE_MAPS_API_KEY');
        $apiUrl = "https://routing.openstreetmap.de/routed-car/route/v1/driving/".$from_latlong.";".$to_latlong."?alternatives=false&overview=full&steps=false";
//        $distance_data = file_get_contents($apiUrl);
//        $distance_arr = json_decode($distance_data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $distance_arr = json_decode($result);
        curl_close($ch);

        if($distance_arr){
            if($distance_arr->code=="Ok")
                return $distance_arr->routes[0]->distance/1000;
            else
                return 0;
        }else{
            return  0;
        }

    }

    function directDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo,$ratio=1)
    {
        $earthRadius = 6371000;
        $latitudeFrom = floatval(trim($latitudeFrom));
        $longitudeFrom = floatval(trim($longitudeFrom));
        $latitudeTo = floatval(trim($latitudeTo));
        $longitudeTo = floatval(trim($longitudeTo));
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $dist =  $angle * $earthRadius;
        $distance = $dist * $ratio / 1000;
        return $distance;
    }

    public function expectedDitance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo){
        $distanceByOpenStreetMap = floatval($this->openStreetMapDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo));
        if($distanceByOpenStreetMap > 0){
            return $distanceByOpenStreetMap;
//            $ratio = $distanceByDirect/$distanceByOpenStreetMap;
//            if( ($ratio>=1.4 || $ratio<=0.6)){
//                return $distanceByDirect;
//            }else{
//                return $distanceByOpenStreetMap;
//            }
        }else{
            $distanceByDirect =  floatval($this->directDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, 1.4));
            return $distanceByDirect;
        }
    }

    public function expectedDuration($distance){
        //if 40 kilometer per hour
        $distance = floatval($distance);
        if($distance>0){
            $time =  round($distance*1.5,2);
            if($time > 3){
                return $time;
            }else{
                return 3;
            }
        }
        else{
            return 3;
        }

    }
    public function car_types()
    {
        $car_types = Car_type::all();

        return response()->json(
            [
                'message'=>'car_types',
                'data'=> [
                    'cars'=> $car_types
                ]
            ]
        );
    }

    public function checkAvailableArea($lat , $long){

        $areas  = Area::all();
        if($areas)
        {
            $x = false;
            foreach ($areas as $area){
                $pois = Border::where('area',$area->id)->orderBy('order_')->get();
                if(count($pois)!=0)
                {
                    $polygon = array();
                    foreach ($pois as $poi){
                        $areaPoint = $poi->latitude." ".$poi->longitude;
                        array_push($polygon, $areaPoint);
                    }
                    $lastPoint = $pois[0]->latitude." ".$pois[0]->longitude;
                    array_push($polygon,$lastPoint);
                    //print_r($polygon);echo "<br>";
                    $points = array($lat." ".$long);
                    $pointLocation = new PointLocationController();
                    foreach($points as $key => $point) {
                        $x = $pointLocation->pointInPolygon($point, $polygon) ;
                    }
                    if($x==true){
                        return $x;
                    }
                }
            }
            return $x;
        }
    }


    public function update(Request $request)
    {
        $messages = [
            'car_image.mimes' => 'خطأ في نمط الصورة',
            'car_image.max' => 'الرجاء التأكد من أن حجم الصورة المرفقة لايتجاوز 2 ميغابايت',
           ];
        $validator = Validator::make($request->all(), [
            'car_type_id' => 'required',
            'car_image'=> 'mimes:png,jpg|max:2048',
        ],$messages );

        if ($validator->fails())
        {
            return response()->json(
                [
                    'message'=>'Driver Register',
                    'data'=> [  'max'=> str_contains($validator->errors() ,'image')?$messages['car_image.max']:'',
                        'english_error'=>'This Data is not valid',
                    ]
                ]
            );
        }
        $car = Car::where('driver_id',$request->driver_id)->first();
        if ($file = $request->file('car_image')) {
            //store file into document folder
            $image = $file->store('public/cars');
            $image = str_replace("public/", "", $image);
            $car->image = $image;
        }
        $car->car_type = $request->car_type_id;
        $car->passenger_number = $request->passenger_number;
        $car->plate = $request->plate;
        $car->year = $request->year;
        $car->mark = $request->brand;
        $car->plate_city_id = $request->plate_city_id;

        if( is_numeric($request->car_model) && $request->car_model != 0){
            $car->car_model = $request->car_model;
        }else{
            //make a new car model
            $car_model = New Car_model();
            $car_model->model  = $request->model_name;
            $car_model->brand_id  = $request->brand_id;
            $car_model->save();
            $car->car_model = $car_model->id;
        }
        if ($car->save())
        {
            return response()->json([
                "success" => true,
                "message" => "successfully updated",
                'data'=> [
                    'arabic_error' =>'',
                    'english_error' =>'',
                    'arabic_result' => 'تم تعديل البيانات',
                    'english_result' => 'successfully uploaded',
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لم يتم تعديل البيانات',
                    'english_error' => 'Driver Not updated!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function checkIfInsideCircle($centerLat, $centerLong, $driverLat, $driverLong, $radius){
        $longA     = $centerLong*(M_PI/180); // M_PI is a php constant
        $latA     = $centerLat*(M_PI/180);
        $longB     = $driverLong*(M_PI/180);
        $latB     = $driverLat*(M_PI/180);

        $subBA       = bcsub ($longB, $longA, 20);
        $cosLatA     = cos($latA);
        $cosLatB     = cos($latB);
        $sinLatA     = sin($latA);
        $sinLatB     = sin($latB);

        //in km
        $distance = 6371*acos($cosLatA*$cosLatB*cos($subBA)+$sinLatA*$sinLatB);
        //echo $distance ; //in km
        $distanceInMeter = $distance*1000;
        if($distanceInMeter <= $radius){
            return true;
        }else{
            return false;
        }
    }

    public function test(){

//        $dist = $this->openStreetMapDistance(33.4953794,36.2920428,33.454325,36.3295733);
//        echo $dist;
//        echo "-";
//         $driverCompensation = 2000 * $dist;
//        echo $driverCompensation;


        //driver compensation
        $comp1 = new DriverCompensation();
        $comp2 = new DriverCompensation();
        $comp3 = new DriverCompensation();
        $comp4 = new DriverCompensation();
        $compensationPerKilo  = $this->setting->compensation_driver_per_kilo;
       // echo $compensationPerKilo;
        $trip0 = Trip::find(2240);
        $comp1->addItem(33.4875693, 36.296328, $trip0, $compensationPerKilo);

//        $trip1 = Trip::find(2263);
//        $comp2->addItem(33.5318339, 36.1879576, $trip1, $compensationPerKilo);

//        $trip2 = Trip::find(2278);
//        $comp3->addItem(33.5324501, 36.2473134, $trip2, $compensationPerKilo);
//
//        $trip3 = Trip::find(2285);
//        $comp4->addItem(33.5318984, 36.1799429, $trip3, $compensationPerKilo);


    }



}





