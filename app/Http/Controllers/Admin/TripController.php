<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Balance;
use App\Models\Cancel_reason;
use App\Models\Cancel_reason_text;
use App\Models\Car_type;
use App\Models\Coordinate;
use App\Models\Counter;
use App\Models\Daily_kpis;
use App\Models\DriverCompensation;
use App\Models\FreeTripOffer;
use App\Models\Invoice;
use App\Models\MultiTrip;
use App\Models\MultiTripOffers;
use App\Models\Note;
use App\Models\Notification;
use App\Models\Offers;
use App\Models\OffersCodeTaken;
use App\Models\Rate_trip;
use App\Models\Setting;
use App\Models\Sum_money;
use App\Models\Trip;
use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Models\TripDetails;
use App\Models\TripSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use stdClass;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $status = array();
    protected $key;
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('auth:admin');
        $this->status = array('0'=>__('menus.Pending'),
            '1'=>__('menus.Approved'),
            '2'=>__('menus.Arrived_to_customer'),
            '3'=>__('menus.In_the_way'),
            '4'=>__('menus.Arrived_to_destination_location'),
            '5'=>__('menus.Cancelled'),
            '6'=>__('menus.Scheduled_Trip'));
        $this->key  = env('GOOGLE_MAPS_API_KEY');
//        App::setLocale('ar');
        session()->put('locale', 'ar');

    }

    public function index()
    {
        $title = 'Trips';
        $t = new Trip();
        //$trips = Trip::all();3
        var_dump(App::getLocale()."-0");
        $trips = $t->getlastNTrips(900);
        $status = $this->status;
       //var_dump($status);
        $cancelReasons = Cancel_reason::all();
        $U = new User();
        $availableDrivers = $U->getAvailableDrivers();
        $trips1 = $t->pendingTrips();
        //$trips1 = Trip::with('user')->whereIn('status',[0,6])->where('driver_id',0)->get();
        $numOfPendingTrips = ($trips1)?count($trips1):0;



        $scheduledTrips = $t->getScheduledTrip();
        return view('admin.trips.index',compact('trips','title','status','cancelReasons','availableDrivers','numOfPendingTrips','scheduledTrips'));
    }

    public function create()
    {
        $key = env('GOOGLE_MAPS_API_KEY');
        $title =  __('menus.Trips');
        $status = $this->status;

        $u = new User();
        $users = $u->getAllUsers();
        $carTypes = Car_type::all();
        return view('admin.trips.create',compact('title','key','carTypes','users'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFaqRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //var_dump($request->all());exit();
        $trip = new Trip();
        if($request->user_==1){
            $trip->user_id = $request->user_id_;
        }else{
            $trip->user_id = 3592;
        }
        $carContObj = new CarController();
        if(!$carContObj->checkAvailableArea($request->latitude_to, $request->longitude_to) || !$carContObj->checkAvailableArea($request->latitude_from, $request->longitude_from))
        {
            Session::flash('alert-danger', __('message.not_avialable_area'));
            return redirect('admin/trips/create');
        }else{
            //Scheduled
            if($request->type_==3){
                $timeOfTrip = $trip->getDifferenceOfTimeInMinutes($request->trip_date_,Carbon::now());
                if($timeOfTrip<30){
                    Session::flash('alert-danger',  __('message.trip_must_be_after_30_mins'));
                    return redirect('admin/trips/create');
                }
                $trip->is_scheduled = 1;
                $trip->trip_date =  $request->trip_date_;
            }else{
                $trip->trip_date = Carbon::now();
            }

            $trip->latitude_from = $trip->truncate($request->latitude_from,10);
            $trip->longitude_from = $trip->truncate($request->longitude_from,10) ;
            $trip->location_from = $request->location_from;

            $trip->latitude_to =  $trip->truncate($request->latitude_to,10);
            $trip->longitude_to = $trip->truncate($request->longitude_to,10) ;
            $trip->location_to = $request->location_to;
            $trip->car_type_id = $request->car_type_id_;

            $trip->silence_trip = 0;
            $trip->enable_discount = 0;
            $trip->note = ($request->note)?$request->note:'';
            //second phone number for user
            $trip->second_number =  ($request->second_number_)?$request->second_number_:'';
            $trip->is_multiple = 0;
            //get last serial number and generate one
            $trip->serial_num = $trip->getLastSerialNumber();

            if($trip->save()){
                $tripObj = new \App\Http\Controllers\Api\TripController();
                //save trip details
                $tDetails = new TripDetails();
                $tDetails->expected_distance = $request->distance;
                $tDetails->expected_duration = $request->duration;
                $tDetails->expected_price = $request->price;
                $tDetails->trip_id = $trip->id;
                $tDetails->ip_user = '';
                $tDetails->ip_driver = '';
                $tDetails->save();
                $tripObj->sendNotificationsToDrivers($trip->id, 0, $request->price, $request->distance,0);
                Session::flash('alert-success',__('message.trip_added'));
                return redirect('admin/trips');
            }else{
                Session::flash('alert-danger', __('message.try_again_later'));
                return redirect('admin/trips/create');
            }
        }
    }

    public function  showPrice(Request $request){
        //ajax search
        $carTypeId = $request->get('car_type_id');
        $fromPlaceId = $request->get('from_place_id');
        $toPlaceId = $request->get('to_place_id');
        $output = array();

        if ($request->ajax()) {
            $output['car_type_id'] = $carTypeId;

            $fromData = $this->getCoordinateByPlaceId($fromPlaceId, $this->key ,'from' );
            $toData = $this->getCoordinateByPlaceId($toPlaceId, $this->key ,'to');
            if($fromData['status'] && $toData['status']) {
                $carContObj = new CarController();
                $tripObj = new \App\Http\Controllers\Api\TripController();
                $carType = Car_type::find($carTypeId);

                $distance = $carContObj->expectedDitance($fromData['latitude_from'], $fromData['longitude_from'], $toData['latitude_to'], $toData['longitude_to']);
                $distance = ($distance > 1) ? $distance : 1;
                if ($distance >= 10 && $distance <= 20) {
                    $distance *= 1.05;
                }
                $output['distance'] = round($distance, 2);
                $expectedDuration = floatval($carContObj->expectedDuration($distance));
                $output['duration'] = $expectedDuration;
                $estimatedPrice = $tripObj->preEstimatePrice($distance , $carType->price);
                $output['price'] = $estimatedPrice;

                $output['latitude_from'] = $fromData['latitude_from'];
                $output['longitude_from'] = $fromData['longitude_from'];
                $output['location_from'] = $fromData['location_from'];

                $output['latitude_to'] = $toData['latitude_to'];
                $output['longitude_to'] = $toData['longitude_to'];
                $output['location_to'] = $toData['location_to'];
            }

            return $output;
        }

    }

    public function getCoordinateByPlaceId($placeId, $key, $fromOrTo){
        $results = array();
        $details_url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&key=$key&language=ar";
        $response = file_get_contents($details_url);
        $data = json_decode($response, true);

        if($data["status"]=="OK"){
            $results['status'] = true;
            $location = "location_".$fromOrTo;
            $results[$location] = $data["result"]["formatted_address"];
            // Return the data (e.g., formatted address) back to the frontend
            $latitude = "latitude_".$fromOrTo;
            $results[$latitude] = $data["result"]["geometry"]["location"]["lat"];
            $longitude = "longitude_".$fromOrTo;
            $results[$longitude] = $data["result"]["geometry"]["location"]["lng"];

        }else{
            $results['status'] = false;
        }

        return $results;
    }

    public function canceled_trips()
    {
        $title = __('menus.Cancelled_trips');
        $trips = Trip::where('status',5)->get();

        return view('admin.trips.canceled_trips',compact('trips','title'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $key = env('GOOGLE_MAPS_API_KEY');
        $status = $this->status;
        $trip = Trip::with('noteTrip')->find($id);


        if($trip->is_multiple){
            $multitrips = MultiTrip::where('trip_id',$id)->get();
        }else{
            $multitrips = array();
        }

        //map
        $coor = new Coordinate();
        $driverCoordinatesArray = array();
        $centerLat = '33.52055680700218' ;$centerLon ='36.29604923082682';
        if($trip->driver){
            $driverCoordinates = $coor->driverCoordinatesDuringTrip($trip, $trip->driver->id);
            $i=0;
            $activeMap = true;
            if($driverCoordinates){
                foreach ($driverCoordinates as $coordinte){
                    //echo $coordinte->latitude;echo "<br>";
                    $driverCoordinatesArray[$i] = ['x',$coordinte->latitude, $coordinte->longitude,$i+1 ];
                    $i++;
                }
            }else{
                $activeMap = false;
            }

            if(!empty($driverCoordinatesArray)){
                if(isset($driverCoordinatesArray[2])){
                    $centerLat = $driverCoordinatesArray[2][1];
                    $centerLon = $driverCoordinatesArray[2][2];
                }else{
                    $activeMap = false;
                }
            }
        }else{
            $activeMap = false;
        }

        //prices
        $tripDetails = TripDetails::where('trip_id',$trip->id)->first();
        if($tripDetails){

            $expectedPrice = round($tripDetails->expected_price,2);
        }
        else
            $expectedPrice = 0;

        $distance = 0 ;
        $user = User::find($trip->user_id);
        $userGender =($user) ?($user->gender!=0)?($user->gender==1)?__('page.male'):__('page.female'):'':'---';
        $driver = User::find($trip->driver_id);
        if($driver) {
            $driverGender =($driver->gender!=0)?($driver->gender==1)?__('page.male'):__('page.female'):'';
            //distance
            $counter = new Counter();
            if($counter->getLatestCounter($trip->id, $trip->driver_id))
            $distance = round($counter->getLatestCounter($trip->id, $trip->driver_id)->whole_distance,1);
        }else{
            $driverGender = '---';
        }
        if(is_null($trip->arrive_to_customer_time)){
            $timeToArriveDriver = "---";
        }
        else{
            $timeToArriveDriver = ceil($trip->getDifferenceOfTimeInMinutes($trip->trip_date,$trip->arrive_to_customer_time));
        }
        if(is_null($trip->arrive_to_customer_time) || is_null($trip->start_date)){
            $timeToArriveCustomer = "---";
        }
        else{
            $timeToArriveCustomer = ceil($trip->getDifferenceOfTimeInMinutes($trip->arrive_to_customer_time,$trip->start_date));
        }
        if(is_null($trip->end_date) || is_null($trip->start_date)){
            $tripDuration = "---";
        }
        else{
            $tripDuration = ceil($trip->getDifferenceOfTimeInMinutes($trip->end_date,$trip->start_date));
        }
        if(is_null($trip->driver_approve_time) || is_null($trip->trip_date)){
            $timeToWaitCustomer = "---";
        }
        else{
            $timeToWaitCustomer = ceil($trip->getDifferenceOfTimeInMinutes($trip->driver_approve_time,$trip->trip_date));
        }

        //alerts
        $alerts = Alert::all();
        //the rate
        $rate = Rate_trip::where('trip_id',$id)->first();

        //final price
        $invoice = Invoice::where('trip_id',$trip->id)->first();
        if($invoice) {
            $price = $invoice->price;
            $wholePrice = $invoice->price+$invoice->discount;
        }else{
            $price = 0;
            $wholePrice = 0;
        }

        $data = ['trip'=>$trip,
                'status'=>$status ,
                'key'=>$key ,
                'driverCoordinatesArray'=>$driverCoordinatesArray,
                'centerLat'=>$centerLat ,
                'centerLon'=>$centerLon,
                'activeMap'=>$activeMap ,
                'expectedPrice' =>$expectedPrice ,
                'price'=>$price,
                'wholePrice' => $wholePrice,
                'timeToArriveDriver'=>$timeToArriveDriver ,
                'timeToArriveCustomer'=>$timeToArriveCustomer,
                'rate'=>$rate,
                'tripDuration'=>$tripDuration,
                'userGender'=>$userGender,
                'driverGender' => $driverGender,
                'alerts' => $alerts,
                'timeToWaitCustomer' => $timeToWaitCustomer,
                'multitrips' => $multitrips,
                'distance' => $distance];

        return view('admin.trips.view',$data);

    }

    public function destroy($id)
    {
        $trip = Trip::where('id',$id)->first();

        $td = TripDetails::where('trip_id',$trip->id)->first();
        if($td) $td->delete();
        $balances = Balance::where('trip_id',$trip->id)->get();
        if($balances)
            foreach ($balances as $balance){
                $balance->delete();
            }
        $coor = Coordinate::where('trip_id',$trip->id)->get();
        if($coor)
            foreach ($coor as $item){
                $item->delete();
            }
        $counters = Counter::where('trip_id',$trip->id)->get();
        if($counters)
            foreach ($counters as $counter){
                $counter->delete();
            }

        $d = DriverCompensation::where('trip_id',$trip->id)->first();
        if($d) $d->delete();
        $trip->delete();

        return back()->with('success','Offer deleted successfully');
    }


    public function pendingTrips(){
        $title =__('setting.pending_trips');
        $cancelReasons = Cancel_reason::all();

        $trips = Trip::with('user')->whereIn('status',[0,6])->where('driver_id',0)->get();
        $status = $this->status;
        $d = new User();
        $drivers = $d->getAvailableDrivers();
        $driversIds = array();
        foreach ($drivers as $driver){
            $driversIds[] = $driver->id;
        }

        $key = env('GOOGLE_MAPS_API_KEY');

        $coor = new Coordinate();
        $driverCoordinates = $coor->driversLastLocationByIds( $driversIds);

        $driversCoordinatesArray = array();
        $i=0;
        foreach ($driverCoordinates as $coordinte){
            $driversCoordinatesArray[$i] = [$coordinte->name,floatval($coordinte->latitude) , floatval($coordinte->longitude) ,$i+1 ];
            $i++;
        }


        return view('admin.trips.pending_trips',compact('trips','title',
            'status','drivers','driversCoordinatesArray','key','driverCoordinates','cancelReasons'));
    }

    public function active_trips(){
        $title =__('setting.active_trips');

        $trips = Trip::with('driver','user')
        ->where(function($q) {
            $q->whereIn('status',[1,2,3])
                ->where('is_scheduled',0);
        })
        ->orWhere(function($q1){
            $q1->whereIn('status',[2,3])
                ->where('is_scheduled',1);
        })
        ->get();
       // whereIn('status',[1,2,3])->get();
        $status = $this->status;
        return view('admin.trips.active_trips',compact('trips','title','status'));
    }

    public function scheduledTrips(){
        $title =__('setting.Scheduled_Trips');

        $t = new Trip();
        $trips = $t->getScheduledTrip();
        $status = $this->status;

        $driversCoordinatesArray = array();
        $i=0;


        return view('admin.trips.scheduled_trips',compact('trips','title',  'status'));
    }

    public function alertDriver(Request $request){
        //echo $request->text;exit();
        $title =__('setting.active_trips');

        $trip = Trip::find($request->trip_id);
        if($trip){
            $notificationObj = new NotificationsController();
            $data = [
                'trip_id'=> $request->trip_id,
                'notification_type'=>'advertisement',
                'is_driver' =>1
            ];
            $body = $request->text;
            $notificationObj->sendNotifications($trip->driver_id, "Warning", $body ,$data);
            Session::flash('alert-success', __('message.alert_driver'));
            return redirect('admin/trips/view/'.$request->trip_id);
        }
    }

    public function addDriver(Request $request)
    {
        //var_dump($request->all());exit();
        $trip = Trip::find($request->trip_id_);
        if($trip->driver_id == 0){
            $driver_id = $request->driver_id;
            $tripApi = new \App\Http\Controllers\Api\TripController();
            $tripWithDetails = Trip::with('trip_details')->find($request->trip_id_);
            $tripDistance = ($tripWithDetails)?$trip->trip_details->expected_distance:0;
            $preEstimatePrice = $tripApi->preEstimatePrice($tripDistance, $trip->carType->price);
            //$drivers[] = $key;
            $title =($trip->is_scheduled==1)?"رحلة مجدولة جديدة":"رحلة جديدة";
            $data = [
                'trip_id'=> $request->trip_id_,
                'notification_type'=>'New Trip',
                'is_multiple'=>$trip->is_multiple,
                'is_driver' =>1,
                'add_features' =>1
            ];

            $body = "هناك طلب رحلة جديدة ";
            $body .= " - ";
            $body .= " مكان الانطلاق  ".$trip->location_from;
            $body .= " - ";
            $body .= " الوجهة  ".$trip->location_to;
            $body .= " - ";
            $body .= " المسافة  ".$trip->trip_details->expected_distance;
            $body .= " - ";
            $body .= " السعر  ".$preEstimatePrice;

            $notificationObj = new NotificationsController();
            $notificationObj->sendNotifications($driver_id, $title, $body ,$data);

            if(!empty($drivers)){
                $x = new  Notification();
                $x->saveNotification([$driver_id],$title,2, $body, 1,0);
            }

            Session::flash('alert-success', __('message.notification_sent'));
            return redirect('admin/pending_trips/');
        }else{
            Session::flash('alert-danger', __('message.driver_exists'));
            return redirect('admin/pending_trips/');
        }

    }

    public function cancelTrip(Request $request){
        echo $request->back_url;

        $trip = Trip::find($request->trip_id);
        if($trip->status==4){
            Session::flash('alert-danger', __('message.trip_already_ended'));
            //return redirect('admin/trips/');
        }else{

            $cancelReasonText = new Cancel_reason_text();
            $cancelReasonText->user_id = 0;
            $cancelReasonText->trip_id = $request->trip_id;
            $cancelReasonText->reason_id = $request->reason_id;
            $cancelReasonText->reason_text = $request->reason_text;
            $cancelReasonText->is_admin = 1;

            if($cancelReasonText->save()){
                $trip->status = 5;
                $trip->update();
            }

            //re calculate compensation
            $driverCompensation = new DriverCompensation();
            $driverCompensation->reCalculateCompensation($trip);

            $notificationObj = new NotificationsController();
            $title = "إلغاء الرحلة";
            $body = "عذراً، تم إلغاء الرحلة من قبل الإدارة";

            $notificationObj->sendNotifications($trip->user_id , $title, $body,[ 'trip_id'=> $trip->id, 'notification_type'=>'trip', 'is_multiple'=>0,'is_driver' => 0 ]);
            $x = new Notification();
            $x->saveNotification([$trip->user_id ],$title,2,$body, 0,0);
            // if driver exist
            if($trip->driver_id !=0){
                $notificationObj->sendNotifications($trip->driver_id , $title, $body,[ 'trip_id'=> $trip->id, 'notification_type'=>'trip', 'is_multiple'=>0,'is_driver' => 1 ]);
                $x->saveNotification([$trip->driver_id],$title,2,$body, 1,0);
            }
            Session::flash('alert-success', __('message.trip_cancelled'));
            //echo $title ;exit();
            return redirect($request->back_url);
        }
    }

    public function addNote(Request $request)
    {
        $trip = Trip::find($request->trip_id_);
        if($trip)
        {
            if($trip->noteTrip){
                session()->flash('alert-danger', __('message.already_note_existed'));
                return redirect('/admin/trips');
            }else{
                //morph
                $note = new Note();
                $note->note = $request->note;
                $trip->noteTrip()->save($note);
                session()->flash('alert-success', __('message.note_added'));
                return redirect('/admin/trips');
            }

        }
        else{
            session()->flash('alert-danger', __('message.No_data'));
            return redirect('/admin/trips/');
        }
    }
    public function editNote(Request $request)
    {
        $note = Note::find($request->note_id);
        if($note)
        {
            //morph
            $note->note = $request->note;
            $note->save();
            session()->flash('alert-success', __('message.note_edited'));
            return redirect('/admin/trips/view/'.$note->notetable_id );
        }
        else{
            session()->flash('alert-danger', __('message.No_data'));
            return redirect('/admin/trips/view/'.$note->notetable_id);
        }
    }

    public function  search(Request $request){
        //ajax search
        $title = __('label.trips_search');
        $U = new User();
        $drivers = $U->getAllDrivers();
        $users = $U->getAllUsers();
        $se = new Setting();

        $trips = Trip::all();
        $driver_id = $request->get('driver_id');
        $user_id = $request->get('user_id');
        $type = $request->get('type');
        if ($request->ajax()) {
            $query = Trip::query();
            if($driver_id != 0)
                $query->where('driver_id',$driver_id );
            if($user_id != 0)
                $query->where('user_id',$user_id );
            if($type != 0){
                if($type == 1){
                    $query->where('is_scheduled',0 );
                    $query->where('is_multiple',0 );
                }elseif($type == 2){
                    $query->where('is_multiple',1 );
                }else{
                    $query->where('is_scheduled',1 );
                }
            }
            if($request->get('date'))
                $query->whereDate('start_date','>=', $request->get('date'));
            if($request->get('date_to'))
                $query->whereDate('start_date','<=', $request->get('date_to'));
            if($request->get('serial_num'))
                $query->where('serial_num', $request->get('serial_num'));
            if($request->get('with_cancelled_trips')==0){
                $query->where('status','!=' ,5);
            }
            $data = $query->get();
            //$output = '';
            $output =  array('tbody'=>'','tfoot'=>'');
            if (count($data) > 0) {
               // $output = '';
                //$output = array();
                $sum = 0;
                $sumOfCompanyPercentage = 0;
                $sumOfDriverPercentage = 0;
                foreach ($data as $trip) {
                    $output['tbody'] .= '<tr>
                    <td><a href="'.url('admin/trips/view/'.$trip->id).'">'.$trip->serial_num.'</a></td>';
                    $url = ($trip->user)?url('admin/users/view/'.$trip->user->id):'#';
                    $name = ($trip->user)?$trip->user->name:'';
                    $output['tbody'] .= '<td><a href="'.$url.'">'.$name.'</a></td>';
                    $drivername = ($trip->driver)?$trip->driver->name:'';
                    $url = ($trip->driver)?url('admin/drivers/view/'.$trip->driver->id):'#';
                    $output['tbody'] .= '<td> <a href="'.$url.'">'.$drivername.'</a></td>';
                    $output['tbody'] .= '<td>'.$trip->start_date.'</td>';
                    $output['tbody'] .= '<td>'.$this->status[$trip->status].'</td>';
                    $price = ($trip->invoice)?$trip->invoice->price:0;
                    $sum += $price;

                    $companyPercentage = ($trip->invoice)?$trip->invoice->company_percentage:0;

                    $sumOfCompanyPercentage += $companyPercentage;
                    $driverPercentage = $price - $companyPercentage ;
                    $sumOfDriverPercentage += $driverPercentage;
                    $output['tbody'] .= '<td>'.$price.'</td>';
                    $output['tbody'] .= '<td>'.$companyPercentage.'</td>';
                    $output['tbody'] .= '<td>'.$driverPercentage.'</td>';

                    if( $this->isAdmin) {
                        $output['tbody'] .= '<td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                    <i data-feather="more-vertical"></i>
                                     <i style="font-size:14px" class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">

                                    <a class="dropdown-item" href="' . url('admin/trips/view/' . $trip->id) . '">
                                        <i data-feather="eye" class="mr-50"></i>
                                        <span>' . __("page.View") . '</span>
                                    </a>';
                        $output['tbody'] .= '<a class="dropdown-item cancel-trip" data-toggle="modal" data-target="#cancel-trip-with-reason"  data-value="' . $trip->id . '"  >
                                            <i data-feather="stop-circle" class="mr-50"></i>
                                            <span>' . __('page.Cancel') . '</span>
                                        </a>
                                        <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#inlineForm" data-id="' . $trip->id . '">
                                            <i data-feather="clipboard"></i>
                                            <span>' . __('label.add_note') . '</span>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>' . __('page.Delete') . '</span>
                                        </a>
                                    </div>
                                </div>
                        </td>';
                    }
                    $output['tbody'] .= '</tr>';
                }
                $output['tfoot'] = '<tr><td ></td><td ><b>'.'المجموع'.'</b></td><td></td><td></td><td></td><td><b>'.$sum.'</b></td><td><b>'.$sumOfCompanyPercentage.'</b></td><td><b>'.$sumOfDriverPercentage.'</b></td><td></td></tr>';
            } else {
                $output['tbody'] = '<li class="list-group-item">' . 'No results' . '</li>';
                $output['tfoot'] ='';
            }
            return $output;
        }
        return view('admin.trips.search',compact('drivers','users','title','trips'));
    }

    public function endTrip(Request $request){
        //calculate distance
        //calculate time
        $trip = Trip::with('carType')->where('id',$request->tripId)->first();
        if ($trip) {
            $price = 0;

            $expectedDuration = $trip->trip_details->expected_duration;
            //$expectedDuration = $realDuration;
           // $now = date('Y-m-d H:i:s');

            $counter = new Counter();
            $record = $counter->getLatestCounter($request->tripId , $trip->driver_id);

            $invoiceC = new InvoiceController();
            if($record){
                $priceAndDiscount = $invoiceC->calculatePrice($record->whole_distance, $expectedDuration, $trip);
                // update driver location
                $Coo = new Coordinate();
                $x = $Coo->update_location($trip->driver_id, $record->latitude, $record->longitude,$trip->id);
            }
            else{
                $priceAndDiscount = $invoiceC->calculatePrice(0 , $expectedDuration, $trip);
            }

            $price = $priceAndDiscount[0];
            $discount = $priceAndDiscount[1];

            $totalPrice = $this->getTotalPrice($price, $discount);

            //calculate company percentage
            $set = new Setting();
            $companyPercentage = $set->getCompanyPercentage($totalPrice);

            $invoice = new Invoice();
            $executed =$invoice->addInvoice($request->tripId, $price, $discount, $companyPercentage);
            if($executed){
                //calculate money per day for driver
                $z = new SumMoneyController();
                $z->sumMoneyAcheivedPerDay($trip->driver_id,$companyPercentage);

                $b = new Balance();
                $b->addBalanceRecord($trip->driver_id, $companyPercentage, '', 3, false, $trip->id);

                if($discount > 0){
                    //add user discount to driver balance
                    $sumMoney = new Sum_money();
                    if($trip->is_scheduled==1 && $this->tSetting->schedule_trip_discount>0){
                        $scheduleTripDiscount = $this->tSetting->schedule_trip_discount;
                        //$discount += $scheduleTripDiscount;
                        $sumMoney->updateMoney($trip->driver_id, $discount);
                        $b->addBalanceRecord($trip->driver_id, $scheduleTripDiscount, '', 5, false, $trip->id);
                        $offerDiscount =  $discount-$scheduleTripDiscount;
                        if($offerDiscount>0){
                            $b->addBalanceRecord($trip->driver_id, $offerDiscount, '', 2, false, $trip->id);
                        }
                    }else{
                        $sumMoney->updateMoney($trip->driver_id, $discount);
                        $b->addBalanceRecord($trip->driver_id, $discount, '', 2, false, $trip->id);
                    }
                }
            }

            //send invoice notification to user
            $notificationObj = new NotificationsController();
            $notificationObj->sendInvoiceNotification($totalPrice, $trip, $expectedDuration, $request->trip_distance, $price, $discount);

            //update trip status
            $trip->status = 4;
            $trip->end_date = Carbon::now();
            $trip->latitude_to = $request->driver_lat;
            $trip->longitude_to = $request->driver_lng;
            $trip->update();

            $x = new KpiTripController();
            $x->doKPIs($trip, $price);

            //send notification to USER that his trip ended
            $notificationObj = new NotificationsController();
            $data = [
                'trip_id'=> $trip->id,
                'notification_type'=>'trip',
                'is_multiple'=>$trip->is_multiple,
                'is_driver' => 0
            ];
            $body = __('message.trip_ended');
            $notificationObj->sendNotifications($trip->user_id, "نهاية الرحلة", $body, $data);

            //send good bye notification to USER
            $data = [
                'trip_id'=> $trip->id,
                'notification_type'=>'trip',
                'is_multiple'=>$trip->is_multiple,
                'is_driver' => 0
            ];
            $s = new Setting();
            $body = $s->getSetting()->bye_message_arabic;
            $notificationObj->sendNotifications($trip->user_id, "شكراً لكم", $body, $data);


            $msg = __('message.trip_ended_successfully');
            $msg .= "<br>";
            $msg .= " القيمة الكلية:  ".$totalPrice;
            Session::flash('alert-success', $msg);
            return Redirect::back();//($request->back_url);
        }
    }

    public function transformCaptain(Request $request){
//         $request->driver_id;
//         $request->trip_Id;
         $trip = Trip::find($request->trip_Id);
         if($trip){
             $trip->driver_id = $request->driver_id;
             $trip->save();
             session()->flash('alert-success', __('message.driver_changed'));
             return redirect('/admin/trips');
         }
         else{
             session()->flash('alert-danger', __('message.No_data'));
             return redirect('/admin/trips/');
         }
    }

    public function get_trip($id){

        $trip = Trip::find($id);
        $title = "get trip";
        return view('admin.trips.get',compact('trip','title'));
    }

    public function deleteAllTripDetails(Request $request){

        $trip = Trip::where('serial_num',$$request->serialNum)->first();
        //$title = "get trip";
        $td = TripDetails::where('trip_id',$trip->id)->first();
        if($td) $td->delete();
        $balances = Balance::where('trip_id',$trip->id)->get();
        if($balances)
        foreach ($balances as $balance){
            $balance->delete();
        }
        $coor = Coordinate::where('trip_id',$trip->id)->get();
        if($coor)
        foreach ($coor as $item){
            $item->delete();
        }

        $counters = Counter::where('trip_id',$trip->id)->get();
        if($counters)
        foreach ($counters as $counter){
            $counter->delete();
        }

        $d = DriverCompensation::where('trip_id',$trip->id)->first();
        if($d) $d->delete();
        $trip->delete();
        //return view('admin.trips.get',compact('trip','title'));
    }

    public function test()
    {

//        $trip = Trip::find(3861);
//        $off = ($trip->noteTrip)? substr($trip->noteTrip->note, 0, 20) :'';
//        var_dump($off);
        $m = 25322;
        $c = $this->roundUp500($m);
        var_dump($c);

    }


}
