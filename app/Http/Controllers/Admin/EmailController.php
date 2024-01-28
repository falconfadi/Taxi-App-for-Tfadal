<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Profile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function regret_letter($id)
    {
        $profile = Profile::find($id);
        $email = $profile->email;
        $first_name = $profile->first_name;
        $var = new Setting();
        $setting = $var->getSetting();
        $regret_msg = $setting->regret;
        //echo $regret_msg;

        Mail::send('admin.home.regret', array('regret_msg'=>$regret_msg,'first_name'=>$first_name), function($message) use ($email,$first_name) {
            $message
                ->to($email, $first_name)
                ->subject('Regret Message from NIB');
        });

        Session::flash('message','Email was Sent to '.$first_name);
        return redirect('admin/profiles');
    }
}
