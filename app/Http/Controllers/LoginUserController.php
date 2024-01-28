<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginUserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('user-login');
    }

    public function login(Request $request)
    {
//        $title = "Privacy policy";
//        $privacy = Policy::find(1);
//        return view('website.privacy',compact('title','privacy'));
        $user = User::find($request->phone);
        if($user){
            //echo $request->password;
            if($user->phone == $request->phone){
               // echo "done";
                $user->delete();
                Session::flash('alert-success',__('message.account_deleted'));
                return redirect('user-login');
            }
            else{
                Session::flash('alert-danger',__('message.No_data'));
                return redirect('user-login');
            }

        }else{
            Session::flash('alert-danger',__('message.No_data'));
            return redirect('user-login');
        }
    }
}
