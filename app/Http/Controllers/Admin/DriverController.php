<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Car_model;
use App\Models\Car_type;
use App\Models\Career;
use App\Models\Color;
use App\Models\Coordinate;
use App\Models\Counter;
use App\Models\Driver;
use App\Models\FreezeReason;
use App\Models\Notification;
use App\Models\Offers;
use App\Models\Policy;
use App\Models\Setting;
use App\Models\Sum_money;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class DriverController extends Controller
{

    protected $passwordText = 'New%20Password:%20';
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
       // $user =  \Auth::user();
//       $u =  Auth::guard('admin')->user();
//       $p = $u->getAllPermissions();
//
//            foreach($p as $permission){
//                echo  $permission->name ."--";
//            }
//
//        exit();

        $title = __('menus.Drivers');
        $D = new User();
        $drivers = $D->getAllDrivers();//User::where('is_driver',1)->get();
        $det = array();

        $S = new  Setting();
        $maxBalance = $S->getSetting()->max_amount_to_stop_driver;
        //$balance = Sum_money::where()
        $x = new Sum_money();
        $balances = $x->driversBalancesToday();

        $notAvailableDriversIds = $D->getDriversIdswithActiveTrip();

        $freezeReason = new FreezeReason();

        $careers = Career::all();
        return view('admin.drivers.index',compact('drivers','det', 'balances','notAvailableDriversIds','freezeReason','careers'));
    }

    public function verify($id)
    {
        $driver = Driver::where('id',$id)->first();
        if($driver)
        {
            $driver->verified = 1;
            if( $driver->save())
            {
                session()->flash('alert-success', __('message.driver_verified'));
                return redirect('/admin/drivers');
            }
        }
    }

    public function freeze(Request $request)
    {
        //var_dump($request->reason);var_dump($request->driver_id);exit();
        $driver = Driver::findOrFail($request->driver_id);
        if($driver)
        {
//            $data = array('freeze' => 1);
//            $driver->update($data);

            $reason = FreezeReason::create(['user_id'=>$driver->user_id, 'reason'=>$request->reason,'is_freeze'=>1]);

            session()->flash('alert-success', __('message.driver_freezed'));
            return redirect('/admin/drivers');

        }
        else{
            session()->flash('alert-success', 'No Data');
            return redirect('/admin/drivers');
        }
    }

    public function addCareer(Request $request)
    {
        $user = User::find($request->user_id);
        if($user)
        {
            $data = ['career_id'=>$request->career_id];
            $user->update($data);

            session()->flash('alert-success', __('message.career_added'));
            return redirect('/admin/drivers');

        }
        else{
            session()->flash('alert-danger', 'No Data');
            return redirect('/admin/drivers');
        }
    }

    public function deleteToken(Request $request)
    {
        $user = User::find($request->user_id);
        if($user)
        {
            $data = ['device_token'=>''];
            $user->update($data);

            session()->flash('alert-success', __('message.token_deleted'));
            return redirect()->back();

        }
        else{
            session()->flash('alert-danger', 'No Data');
            return redirect()->back();//redirect('/admin/drivers');
        }
    }

    public function unfreeze($id)
    {
        $driver = Driver::findOrFail($id);
        if($driver)
        {
//            $data = array('freeze' => 0);
//            $driver->update($data);

            $reason = FreezeReason::create(['user_id'=>$driver->user_id, 'reason'=>'','is_freeze'=>0]);

            session()->flash('alert-success', 'تم إلغاء تجميد الكابتن');
            return redirect('/admin/drivers');

        }
        else{
            session()->flash('alert-success', 'No Data');
            return redirect('/admin/drivers');
        }
    }

    public function unVerify($id)
    {
        $driver = Driver::findOrFail($id);
        if($driver)
        {
            $data = array('verified' => 0);
            $driver->update($data);

            //$reason = FreezeReason::create(['driver_id'=>$driver->user_id, 'reason'=>'','is_freeze'=>0]);

            session()->flash('alert-success', 'تم إلغاء تثبيت الكابتن');
            return redirect('/admin/drivers');

        }
        else{
            session()->flash('alert-success', 'No Data');
            return redirect('/admin/drivers');
        }
    }

    public function view($id)
    {
        //echo $id;
        $driver = Driver::with('driver_as_user')->findOrFail($id);
        if($driver)
        {
            //get money acheived per this month
            $X = new Sum_money();
            $year = date('Y');
            $month = date('m');
            $result = $X->sumMoneyAcheivedPerMonth($driver->user_id, $month, $year);
            //var_dump($result);exit();
            $sum_amount = (!empty($result))?$result[0]->sum_amount:0;

            $balance = ($X->driverBalance($driver->user_id))?$X->driverBalance($driver->user_id)->balance:0;
            $car = Car::where('driver_id',$driver->user_id)->first();

            $lastTrips = Trip::where('driver_id',$driver->user_id)->orderBy('id','desc')->limit(1)->pluck('start_date')->toArray();

            $trips  = Trip::where('driver_id',$driver->user_id)->get();
            if (!empty($trips)){
                $tripsCount  = count($trips);
            }else{
                $tripsCount = 0;
            }
            return view('admin.drivers.view',compact('driver','car','sum_amount','lastTrips','balance','tripsCount'));
        }
    }

    public function edit($id)
    {
        $title = __('page.Edit_Driver');
        $driver = Driver::with('driver_as_user')->findOrFail($id);
        //var_dump($driver);exit();
        $car = Car::where('driver_id',$driver->user_id)->first();
        $car_model = Car_model::find($car->car_model);
        $car_types = Car_type::all();
        $colors = Color::all();
        $brands = Brand::all();
        return view('admin.drivers.edit',compact('driver','title','car_types','brands','car','car_model','colors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car_model  $car_model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //echo url()->previous();exit();
        $messages = [
            'image.mimes' => trans('validation.mimes'),
            'image.max.file' => trans('validation.max.file'),

        ];

        $driver = Driver::find($request->driver_id);
        $user = User::find($driver->user_id);
            $validator = Validator::make($request->all(), [
                'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
                'phone' => 'required',Rule::unique('users')->ignore($user->id),
            ],$messages);

            if ($validator->fails()) {
                return redirect('admin/drivers/edit/'.$request->driver_id)
                    ->withErrors($validator)
                    ->withInput();
            }
            if($request->hasFile('personal_id_image')){
                $file = $request->file('personal_id_image');
                $file_name = 'drivers/id_lisence/'.md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
                if ($file->move('storage/drivers/id_lisence', $file_name)) {
                    $driver->personal_id_image = $file_name;
                }
            }
            if($request->hasFile('back_personal_id_image')){
                $file = $request->file('back_personal_id_image');
                $file_name = 'drivers/id_lisence/'.md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
                if ($file->move('storage/drivers/id_lisence', $file_name)) {
                    $driver->back_personal_id_image = $file_name;
                }
            }
            $driver->last_name = $request->input('last_name');
            $driver->father_name = $request->input('father_name');
            $driver->birthdate = $request->input('birthdate');
            $driver->marital_status = $request->input('marital_status');

            if ( $driver->update()){

                if($request->hasFile('image')) {
                    $file = $request->file('image');
                    $file_name = 'drivers/'.md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
                    if ($file->move('storage/drivers/', $file_name)) {
                        $user->image = $file_name;
                    }
                }
                $userWithPhone = User::where('phone',$request->input('phone'))->where('id','!=',$user->phone)->first();
                if(!$userWithPhone){
                    $user->phone = $request->input('phone');
                }
                //$user->phone = $request->input('phone');

                $user->name = $request->input('name');

                $user->gender = $request->input('gender');
                $user->address = $request->input('address');
                $user->update();

                $car = Car::where('driver_id',$driver->user_id)->first();
                if($request->hasFile('car_image')){
                    $file = $request->file('car_image');
                    $file_name = 'cars/'.md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
                    if ($file->move('storage/cars/', $file_name)) {
                        $car->image = $file_name;
                    }
                }
                $car->mark = $request->input('brand_id');
                if($request->input('car_model'))
                    $car->car_model = $request->input('car_model');
                $car->plate =  $request->input('plate');
                $car->year =  $request->input('year');
                $car->color_id =  $request->input('color_id');
                $car->car_type =  $request->input('car_type');
                $car->update();
                $request->session()->flash('alert-success', __('message.car_and_driver_updated'));
                return redirect($request->input('back_link'));
            }else{
                $request->session()->flash('alert-danger',  __('message.car_not_updated'));
                return redirect()->back();
            }

        //return redirect('admin/drivers/edit/'.$driver->id);

    }

    public function destroy($id)
    {
        $driver = Driver::find($id);
        if($driver )
        {

            $user = User::find($driver->user_id)->delete();
            $car = Car::where('driver_id',$driver->user_id)->delete();

            $del_driver = Driver::find($id)->delete();
        }
        return back()->with('success','Driver deleted successfully');
    }

    public function finalDelete($id)
    {
        $driver = Driver::find($id);
        if($driver )
        {
            //echo "gg";
            $user = User::find($driver->user_id)->forcedelete();
            $car = Car::where('driver_id',$driver->user_id)->forcedelete();

            $del_driver = Driver::find($id)->forcedelete();
        }
        return back()->with('success','Driver deleted successfully');
    }

    public function driversHaveTrips(){
        $title =__('setting.Drivers_have_trip');
        $U = new User();
        $drivers = $U->getDriverswithActiveTrip();
        $det = array();
        foreach ($drivers  as $driver)
        {
            $x = User::find($driver->id)->drivers_details;
            array_push($det , $x);
        }
        //var_dump($det);exit();
        return view('admin.drivers.driversHaveTrips',compact('drivers','det','title'));
    }


    public function changePassword($id)
    {
        $title = __('menus.change_password');
        $driver = Driver::with('driver_as_user')->findOrFail($id);
        //var_dump($driver);exit();
        //$car = Car::where('driver_id',$driver->user_id)->first();
        //$car_model = Car_model::find($car->car_model);
        //$car_types = Car_type::all();
        //$colors = Color::all();
        return view('admin.drivers.changePassword',compact('driver','title'));
    }

    public function changePasswordUpdate(Request $request)
    {
        $driver = Driver::findOrFail($request->id);
        if($driver)
        {
           // echo $request->password_confirmation;
            $nums = rand(1000,9999);
            $capitalString = "ABCDEFGHIJKLMNOPQRSTUVWZYZ";
            $smallString = "abcdefghijklmnopqrstuvwxyz";
            $specialCharacters = "@#$%";
            $capital = $capitalString[rand(0, strlen($capitalString)-1)];
            $small = $smallString[rand(0, strlen($smallString)-1)];
            $special = $specialCharacters[rand(0, strlen($specialCharacters)-1)];


            $password = $capital.$small.$nums.$special.$special;
            $hashedPassword = Hash::make($password);
            $user = User::find($driver->user_id);
            $user->password = $hashedPassword;
            $user->save();

            $sendSMS = new SendSMSController();
            $msg = $this->passwordText. $password;
            $sendSMS->send($msg,$user->phone );

            session()->flash('alert-success', __('message.password_changed'));
            return redirect('/admin/drivers');

        }
        else{
            session()->flash('alert-danger', __('message.No_data'));
            return redirect('/admin/drivers/change_password/'.$request->id);
        }
    }

    public function driversOnMap(){
        $key = env('GOOGLE_MAPS_API_KEY');
        $title = __('label.drivers_map');

        $coor = new Coordinate();
        $driverCoordinates = $coor->driversLastLocation( );

        $driversCoordinatesArray = array();
        $activeMap = true;
        $i=0;
        foreach ($driverCoordinates as $coordinte){
            //echo $coordinte->latitude;echo "<br>";
            $driversCoordinatesArray[$i] = [$coordinte->name,floatval($coordinte->latitude) , floatval($coordinte->longitude) ,$i+1 ];
            $i++;
        }
        //echo "<pre>";var_dump($driversCoordinatesArray);echo "</pre>";exit();

//        $driversCoordinatesArray1 = array();
//        $driversCoordinatesArray1 =
//                [
//            ['Bondi Beach', -33.890542, 151.274856, 4],
//            ['Coogee Beach', -33.923036, 151.259052, 5],
//            ['Cronulla Beach', -34.028249, 151.157507, 3],
//            ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
//            ['Maroubra Beach', -33.950198, 151.259302, 1]
//
//        ];
        return view('admin.drivers.drivers_on_map',compact('key','title','driversCoordinatesArray'));
    }

    public function  search (Request $request){
        //ajax search
        $title = __('label.driver_search');
        $U = new User();

        $freezeReason = new FreezeReason();
        $name = $request->get('name');
        $gender = $request->get('gender');
        $verified = $request->get('verified');
        $has_trip = $request->get('has_trip');
        if ($request->ajax()) {
            $query = User::query();
            if($name != '')
                $query->where('name',$name );
            if($gender != 0)
                $query->where('gender',$gender );
            if($verified != 2){
                $query->whereHas('drivers_details',function($subQ) use($verified) {
                    $subQ->where('verified',$verified);
                });
            }
            if($has_trip != 2){
                if($has_trip == 1){
                    $query->whereHas('tripsDriver',function($subQ) {
                        $subQ->whereIn('status',[1,2,3]);
                    });
                }else{
                    $query->whereHas('tripsDriver',function($subQ) {
                        $subQ->whereNotIn('status',[1,2,3]);
                    });
                }
            }

            $data = $query->where('is_driver',1)->with('drivers_details')->get();
            $output = '';
            if (count($data) > 0) {
                $output = '';
                foreach ($data as $driver) {
                    $fullName = $driver->name. " ".$driver->drivers_details->father_name." ".$driver->drivers_details->last_name;
                    $output .= '<tr>

                    <td><a href="'.url('admin/drivers/view/'.$driver->drivers_details->id).'">'.$fullName.'</a></td>';
                    $output .= '<td>'.$driver->phone.'</td>';
                    $gender = ($driver->gender!=0)?($driver->gender==1)?__('page.male'):__('page.female'):'';
                    $output .= '<td>'.$gender.'</td>';
                    $verified = ($driver->drivers_details->verified==1)?__('page.Yes'):__('page.No');
                    $output .= '<td>'.$verified.'</td>';
                    if($driver->drivers_details->is_connected==1){
                        $connected = '<div class="badge-wrapper mr-1">
                                    <div class="badge badge-pill badge-light-success">'.__('setting.connected_').'</div>
                                </div>';
                    }else{
                        $connected = '<div class="badge-wrapper mr-1">
                                    <div class="badge badge-pill badge-light-danger">'.__('setting.not_connected_').'</div>
                                </div>';
                    }

                    $output .= '<td>'.$connected.'</td>';
                    $output .= '<td>'.$driver->created_at.'</td>';
                    $balance = ($driver->balance)?$driver->balance->balance:0;
                    $output .= '<td>'.$balance.'</td>';
                    $output .= '<td>'.$driver->drivers_details->birthdate.'</td>';
                    $freezed = ($freezeReason->isFreezed($driver->id)==1)?__('page.Yes'):__('page.No');
                    $output .= '<td>'.$freezed.'</td>';
                    if( $this->isAdmin) {
                        $output .= '<td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                    <i data-feather="more-vertical"></i>
                                     <i style="font-size:14px" class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">

                                    <a class="dropdown-item" href="' . url('admin/trips/view/' . $driver->id) . '">
                                        <i data-feather="eye" class="mr-50"></i>
                                        <span>' . __("page.View") . '</span>
                                    </a>';
                        $output .= '<a class="dropdown-item cancel-trip" data-toggle="modal" data-target="#cancel-trip-with-reason"  data-value="' . $driver->id . '"  >
                                            <i data-feather="stop-circle" class="mr-50"></i>
                                            <span>' . __('page.Cancel') . '</span>
                                        </a>
                                        <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#inlineForm" data-id="' . $driver->id . '">
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
                    $output .= '</tr>';
                }

            } else {
                $output .= '<li class="list-group-item">' . 'No results' . '</li>';
            }
            return $output;
        }
        return view('admin.drivers.search',compact('title'));
    }

    public function driverTrips($id)
    {
        $title = __('menus.driver_trips');
        // $user = User::findOrFail($id);
        $trips = Trip::where('driver_id',$id)->get();
        $tripCtrl = new \App\Http\Controllers\Admin\TripController();
        $status = $tripCtrl->status;
        return view('admin.drivers.driver_trips',compact('trips','title','status'));
    }

    public function test(){
        $carObj = new CarController();
        $counter = new Counter();

//        $distance =  floatval($carObj->directDistance(33.5072084, 36.3166582, $latitude, $longitude, 1.1));
//        echo $distance;
        $record = $counter->getLatestCounter(2090, 740);
        echo $record->id."-";
        $trip = Trip::find(2090);
        echo $trip->id."-";
        //$counter = new Counter();
        $tripObj = new TripController();
        if($record){
            echo "1-";
            $latitude =33.518355;
                $longitude =36.3491017;
            $distance =  floatval($carObj->directDistance($record->latitude, $record->longitude, $latitude, $longitude, 1.1));
            if($distance>0.005 && $distance<5){
                //$counter = new Counter();
                echo "2-";
                $counter->latitude = $latitude;
                $counter->longitude = $longitude;
                $counter->distance = $distance;
                $counter->counter = $record->counter+1;
                $counter->user_id = 740;
                $counter->price =  round($tripObj->PricePerKilometer($distance, $trip->carType->price),2) ;
                //$whole_price = (float)$counter->price + (float)$record->whole_price;
                $counter->whole_price = round((float)$counter->price + (float)$record->whole_price,2);
                $counter->whole_distance = $distance + (float)$record->whole_distance;
                $counter->trip_id = 2090;
                $counter->save();
                //$counter->whole_distance = round($distance + (float)$record->whole_distance,3);
            }
        }
    }

    public function  getDriverByDriverId($id){
        $driver = Driver::with('driver_as_user')->findOrFail($id);
        echo "<pre>";
        var_dump($driver->driver_as_user->id);
        echo "</pre>";
    }





}
