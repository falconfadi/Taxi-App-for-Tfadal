<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cancel_reason;
use App\Models\Car_type;
use App\Models\Company;
use App\Models\Setting;
use App\Models\Trip;
use App\Models\TripDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\PermissionRegistrar;

class CompanyController extends Controller
{

    protected $status = array();
    public function __construct()
    {
        parent::__construct();

        $this->status = array('0'=>__('menus.Pending'),
            '1'=>__('menus.Approved'),
            '2'=>__('menus.Arrived_to_customer'),
            '3'=>__('menus.In_the_way'),
            '4'=>__('menus.Arrived_to_destination_location'),
            '5'=>__('menus.Cancelled'),
            '6'=>__('menus.Scheduled_Trip'));
    }
    public function index()
    {
        $title =  __('menus.employees');
        $companies = Auth::guard('admin')->user();
       // ($this->company);exit();
        $employees = $companies->employees;

        return view('company.index',compact('companies','title','employees'));
    }

    public function edit()
    {
        $title =  __('menus.companies');
        //$id = $this->getUser();
        $company = Auth::guard('admin')->user();//Admin::with('company')->where('admin_id',$id)->first();
        //Auth::guard('admin')->user()->id;

       // var_dump($company);exit();
        $driversIds = array();

        return view('company.edit',compact('company','title'));
    }



    public function update(Request $request){

        $company = Auth::guard('admin')->user();
        $messages = [
            'email.unique' =>  'الرجاء التأكد من عدم تسجيل بريد الكتروني مسجل مسبقاً',
            'name.string' => 'الاسم يجب أن يكون سلسلة محارف '
        ];
        //الرجاء التأكد من عدم تسجيل بريد الكتروني مسجل مسبقا
        $validator = Validator::make($request->all(), [
            'phone' =>'required',
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($company->id)]
            /* 'password' => ['required'],*/
        ],$messages);

        if ($validator->fails())
        {
            return redirect('admin/company/edit/')->withErrors($validator);
        }

        $company ->update(array(
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => 1
        ));

        Session::flash('alert-success','تم تعديل تفاصيل الشركة');
        return redirect('admin/company/edit');
    }

    public function addEmployees(){
        $title =  __('menus.companies');
        //$id = $this->getUser();
        $company = Auth::guard('admin')->user();//Admin::with('company')->where('admin_id',$id)->first();

        return view('company.add_employees',compact('company','title'));
    }

    public function searchByPhone (Request $request){
        //ajax search
        $title = __('label.trips_search');

        $phone = $request->get('phone');
        if ($request->ajax()) {
            $query = User::query();
            $query->where('phone',$phone );
            $data = $query->first();
            $output = '';
            if ($data) {
                return $data;
            } else {
                return false;
            }
        }
    }

    public function addEmployeeStore(Request $request){
         //var_dump($request->phone_);exit();
        $company = Auth::guard('admin')->user();
        $user = User::where('phone',$request->phone_)->first();
        if($user)
        {
            $company->employees()->attach([$user->id]);
            Session::flash('alert-success',__('message.employee_added'));
            return redirect('company/employees');
        }
        else {
            Session::flash('alert-danger',__('message.No_data'));
            return redirect('company/add_employees');
        }
    }

    public function destroy($id)
    {
       Auth::guard('admin')->user()->employees()->detach([$id]);
        //$res = Offers::find($id)->delete();
        return back()->with('success','Offer deleted successfully');
    }

    public function trips()
    {
        $title =  __('menus.trips');
        $status = $this->status;
        $company = Auth::guard('admin')->user();
        // ($this->company);exit();
        //$trips = $company->trips;

        $trips = $company->trips;
        $cancelReasons = Cancel_reason::all();
        return view('company.trips',compact('company','title','trips','cancelReasons','status'));
    }

    public function addTrip()
    {
        $key = env('GOOGLE_MAPS_API_KEY');
        $title =  __('menus.Trips');
        $status = $this->status;
        $employees = Auth::guard('admin')->user()->employees;
        // ($this->company);exit();

        $carTypes = Car_type::all();
        return view('company.create_trip',compact('title','key','carTypes','employees'));
    }

    public function addTripStore(Request $request){
        //var_dump($request->all());exit();
        $company = Auth::guard('admin')->user();
        //$user = User::where('phone',$request->phone_)->first();

        $trip = new Trip();
        $trip->user_id = $request->users[0];
        $trip->latitude_from = $trip->truncate($request->latitude_from,10);
        $trip->longitude_from = $trip->truncate($request->longitude_from,10) ;
        $trip->latitude_to =  $trip->truncate($request->latitude_to,10) ;
        $trip->longitude_to = $trip->truncate($request->longitude_to,10) ;
        $trip->location_from = $this->getaddress($request->latitude_from,$request->longitude_from);
        $trip->location_to = $this->getaddress($request->latitude_to,$request->longitude_to);
        $trip->car_type_id = $request->car_type_id;
        $trip->trip_date = $request->trip_date;
        $trip->status = 6;
        $trip->silence_trip = 0;
        $trip->enable_discount = 0;
        $trip->is_scheduled = 1;
        $trip->is_company = 1;
        $trip->note = ($request->note)?$request->note:'';
        //second phone number for user
        $trip->second_number =  ($request->second_number)?$request->second_number:'';
        $trip->is_multiple = 0;

        //get last serial number and generate one
        $trip->serial_num = $trip->getLastSerialNumber();

        if($trip->save()){
            //add trip to the company trips
            $company->trips()->attach([$trip->id]);
            //add employees to this trip
            $trip->employees()->attach($request->users);
            $carContObj = new CarController();
            $tripObj = new TripController();
//            if(/*!$carContObj->checkAvailableArea($request->destiniation_lat, $request->destiniation_lng)*/false )
//            {
//
//            }else{
                $carType = Car_type::find($request->car_type_id);
                //$distance = 0;

                $distanceAndDuration =  $carContObj->googleMapDistance($trip->latitude_from, $trip->longitude_from, $trip->latitude_to, $trip->longitude_to);

                $distance =  floatval($distanceAndDuration[0]);
                $duration =  floatval($distanceAndDuration[1]);

                //save trip details
                $tDetails = new TripDetails();
                $tDetails->expected_distance = $distance;
                $tDetails->expected_duration = $duration;
                $tDetails->trip_id = $trip->id;
                $tDetails->save();

                $estimatedPrice = $tripObj->preEstimatePrice($distance , $carType->price);
                //$time_to_arrive_driver = ceil($carContObj->timeToArriveDriver($request->pickup_lat, $request->pickup_lng));

                //$tripAPI = new TripController();
                $tripObj->sendNotificationsToDrivers($trip->id, 0, $estimatedPrice, $distance,0);
                $message = __('message.trip_added')."<br>";
                $message .= " التكلفة التقريبية: ".$estimatedPrice." ل.س ";
                Session::flash('alert-success',$message);
                return redirect('company/trips');
            //}
        }
        else {
            Session::flash('alert-danger',__('message.No_data'));
            return redirect('company/add_trip');
        }
    }


    function getaddress($lat, $lng)
    {
        //$url = 'http://maps.google.com/maps/api/geocode/json?latlng==' . trim($lat) . ',' . trim($lng) ;
        //$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($lat) . ',' . trim($lng) . '&sensor=false';
        //google map api url
        $key = env('GOOGLE_MAPS_API_KEY');
        $url = 'https://maps.google.com/maps/api/geocode/json?key='.$key.'&latlng='.trim($lat).','.trim($lng);

        // send http request
        $geocode = file_get_contents($url);
        $json = json_decode($geocode);

        if($json->status=="OK")
        {
            $address = $json->results[0]->formatted_address;
            return $address;
        }
        return "--";

    }

    public function test(){
        //var_dump($this->truncate(33.51753042096148,10));
        $trip = new Trip();

        $latitude_from ='33.5112330440881';
            $longitude_from ='36.31439213769532';
                $latitude_to ='33.51581310000001';
                    $longitude_to ='36.32362880159234';
        $trip->location_from = $this->getaddress($latitude_from,$longitude_from);
        $trip->location_to = $this->getaddress($latitude_to,$longitude_to);
        $trip->latitude_from = $trip->truncate($latitude_from,10);
        $trip->longitude_from = $trip->truncate($longitude_from,10) ;
        $trip->latitude_to =  $trip->truncate($latitude_to,10) ;
        $trip->longitude_to = $trip->truncate($longitude_to,10) ;
        var_dump($trip);echo '<br>';
        $carContObj = new CarController();
        $distanceAndDuration =  $carContObj->googleMapDistance($trip->latitude_from, $trip->longitude_from, $trip->latitude_to, $trip->longitude_to);
        var_dump($distanceAndDuration);
    }


}
