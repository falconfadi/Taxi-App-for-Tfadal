<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\Controller;
use App\Models\Coordinate;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\App;

use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\PermissionRegistrar;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('auth:admin');
//        App::setLocale('ar');
//        session()->put('locale', 'ar');


//        $permissionsNames = array();
//        $permissions = Auth::guard('admin')->user()->getAllPermissions();
//        foreach ($permissions as $p){
//            $permissionsNames[] = $p->name;
//        }


    }
    public function index()
    {
        $title = __('auth.Administration_Dashboard');
        $local =  session()->get('locale');

        $setting = $this->setting;
        $appTitle = ($local=='ar')?$setting->title_ar:$setting->title;

        $key = env('GOOGLE_MAPS_API_KEY');

        $coor = new Coordinate();
        $driverCoordinates = $coor->driversLastLocation( );

        $driversCoordinatesArray = array();
        $i=0;
        foreach ($driverCoordinates as $coordinte){
            $driversCoordinatesArray[$i] = [$coordinte->name,floatval($coordinte->latitude) , floatval($coordinte->longitude) ,$i+1 ];
            $i++;
           // $driversNames = $coordinte->name." ".$coordinte->father_name." ".$coordinte->last_name;
        }
        return view('admin.home',compact('title','appTitle','driversCoordinatesArray','key','driverCoordinates'));
    }

    public function changePassword(){
        $title = __('label.change_password');

        return view('admin.change_password',compact('title'));
    }

    public function changePasswordStore(Request $request){
        //$id = Auth::guard('admin')->user()->id;
        $user = Auth::guard('admin')->user();
       // var_dump($user);exit();
        if($user){
            $hashedPassword = Hash::make($request->password);
            $user->password = $hashedPassword;
            $user->save();
        }

        Auth::guard('admin')->logout();
        return redirect('admin/login');

    }
}
