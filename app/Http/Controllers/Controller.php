<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TripDetails;
use App\Models\TripSetting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Traits\HasRoles;

//use Auth;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests,HasRoles;

    //protected $isAdmin = Auth::guard('admin')->user()?->role('Super-Admin');
    protected $id;
    protected $isAdmin;
    protected $permissionsNames;
    protected $setting;
    protected $tSetting;
    public function __construct()
    {
        App::setLocale('ar');
        session()->put('locale', 'ar');

        $x = new Setting();
        $setting = $x->getSetting();
        $this->setting = $setting;
        View::share('setting',$setting);

        $this->tSetting = TripSetting::find(1);


        $this->middleware(function ($request, $next) {
            //get the role of user is he is super admin or not
            $xx = (Auth::guard('admin')->user())?Auth::guard('admin')->user()->hasRole('Super-Admin'):false;
            $this->isAdmin = $xx;
            View::share('isAdmin', $xx);

            //get permissions for user and share them for blade files
            if(Auth::guard('admin')->check()) {
                $permissionsNames = array();
                $permissions = Auth::guard('admin')->user()->getAllPermissions();
                foreach ($permissions as $p) {
                    $permissionsNames[] = $p->name;
                }
                $this->permissionsNames = $permissionsNames;
                View::share('permissionsNames', $permissionsNames);
            }

            return $next($request);
        });
    }

    public function getUserId()
    {
        $x = Auth::guard('admin')->id();
        return $x;
    }
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $e='';
        foreach ($error as $item=>$key){
            //$e = $key[0];
            $e = $item;
            break;
        }
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    public function roundUp($number){
        return ceil($number / 100) * 100;
    }
    //remove points and round up 300.1=>301
    public function removePointsUp($number){
        return ceil($number );
    }

    public function removePointsDown($number){
        return floor($number );
    }

    public function getTotalPrice($price, $discount){
        $totalPrice = $price + $discount;

//        $tripDetails = TripDetails::where('trip_id',$trip_id)->first();
//        if($tripDetails){
//            if($tripDetails->expected_price > $totalPrice){
//                $totalPrice = $tripDetails->expected_price;
//            }
//        }
        return $totalPrice;
    }
}
