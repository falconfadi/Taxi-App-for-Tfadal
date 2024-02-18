<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TripSetting;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function index()
    {
        $title = 'Settings';
        $s = new Setting();
        $setting = $s->getSetting();
        //var_dump($sett);exit();
        return view('admin.settings.index',compact('setting','title'));
    }
    public function edit()
    {
        $title = __('menus.Settings');
        $var = new Setting();
        $setting = $var->getSetting();
        $tSetting = TripSetting::find(1);
        return view('admin.settings.edit',compact('tSetting','setting','title'));
    }


    public function update(Request $request)
    {
        $setting = Setting::find(1);
        //var_dump($request->all());exit();
        $setting->phone = $request->input('phone');
        $setting->email = $request->input('email');
        $setting->facebook = $request->input('facebook');
        $setting->whatsapp_number = $request->input('whatsapp_number');
        $setting->ios_app_url = $request->input('ios_app_url');
        $setting->ios_version = $request->input('ios_version');
        $setting->android_version = $request->input('android_version');

        $setting->ios_app_url_driver = $request->input('ios_app_url_driver');
        $setting->ios_version_driver = $request->input('ios_version_driver');
        $setting->android_version_driver = $request->input('android_version_driver');

        $setting->arabic_currency = $request->input('arabic_currency');
        $setting->english_currency = $request->input('english_currency');
        $setting->time_to_refresh_counter = $request->input('time_to_refresh_counter');
        $setting->driver_accept_time_out = $request->input('driver_accept_time_out');
        if($request->input('price_min_stop'))
        $setting->price_min_stop = $request->input('price_min_stop');
        else
        $setting->price_min_stop = 0;
        if($request->input('price_min'))
        $setting->price_min = $request->input('price_min');
        else
        $setting->price_min = 0;
        if($request->input('price_open'))
        $setting->price_open = $request->input('price_open');
        else
        $setting->price_open = 0;

        $setting->twitter_link = $request->input('twitter_link');
        $setting->instagram_link = $request->input('instagram_link');
        $setting->youtube_link = $request->input('youtube_link');
        $setting->site_url = $request->input('site_url');


        if($request->input('add_google_cost'))
            $setting->add_google_cost = 1;
        else
            $setting->add_google_cost =0;

        if($request->input('is_enabled_points'))
            $setting->is_enabled_points = 1;
        else
            $setting->is_enabled_points =0;

        if($request->input('discount_driver'))
            $setting->discount_driver = $request->input('discount_driver');
        else
            $setting->discount_driver =0;

        $setting->company_percentage = $request->input('company_percentage');

        if($request->input('enable_payment'))
            $setting->enable_payment = 1;
        else
            $setting->enable_payment =0;

        if($request->input('enable_PIN'))
            $setting->enable_PIN = 1;
        else
            $setting->enable_PIN =0;

        $setting->compensation_driver_per_kilo = $request->input('compensation_driver_per_kilo');

        $setting->long_distance_start_after = $request->input('long_distance_start_after');

        $setting->connected_message_arabic = $request->input('connected_message_arabic');
        $setting->connected_message_english = $request->input('connected_message_english');
        $setting->welcome_message_arabic = $request->input('welcome_message_arabic');
        $setting->welcome_message_english = $request->input('welcome_message_english');

        //message to user when end the trip
        $setting->bye_message_english = $request->input('bye_message_english');
        $setting->bye_message_arabic = $request->input('bye_message_arabic');


        $setting->registration_welcome_msg_ar = $request->input('registration_welcome_msg_ar');
        $setting->registration_welcome_msg_en = $request->input('registration_welcome_msg_en');

        //max number of money where driver stopped
        $setting->max_amount_to_stop_driver = $request->input('min_amount_to_stop_driver');
        //fadi
        $setting->min_amount_to_stop_driver = $request->input('min_amount_to_stop_driver');

        //message to driver when balance run out
        $setting->alert_balance_english = $request->input('alert_balance_english');
        $setting->alert_balance_arabic = $request->input('alert_balance_arabic');

        $setting->first_circle_radius = $request->input('first_circle_radius');
        $setting->other_circles_ratio = $request->input('other_circles_ratio');
        $setting->driver_accept_trip_timeout = $request->input('driver_accept_trip_timeout');

        if($request->input('is_show_furniture'))
            $setting->is_show_furniture = 1;
        else
            $setting->is_show_furniture =0;

        $setting->sos_number = $request->input('sos_number');
        $setting->sos_number_2 = $request->input('sos_number_2');

        $setting->time_to_update_driver_counter = $request->input('time_to_update_driver_counter');
        $setting->time_to_update_customer_counter = $request->input('time_to_update_customer_counter');
        $setting->time_to_request_driver_location = $request->input('time_to_request_driver_location');
        $setting->time_to_request_customer_location = $request->input('time_to_request_customer_location');

        if($request->hasFile('logo'))
        {
            $file = $request->file('logo');
            $file_name = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move('storage/', $file_name)) {
                $setting->logo = $file_name;
            }
        }

        if ($setting->update()) {
            $tSetting = TripSetting::find(1);
            $tSetting->schedule_trip_discount = $request->input('schedule_trip_discount');
            $tSetting->gift_captain_girl = $request->input('gift_captain_girl');
            $tSetting->distance_compensation_scheduled = $request->input('distance_compensation_scheduled');
            $tSetting->factory_price = $request->input('factory_price');
            $tSetting->gift_captain_male = $request->input('gift_captain_male');

            $tSetting->update();
            Session::flash('alert-success',__('message.Saved_successfully'));
            return redirect('admin/settings/edit');
        } else {
            Session::flash('alert-success','message.not_Saved');
            return redirect('admin/settings/edit');
        }

    }
    public function settings_trips(Request $request)
    {
        $title = 'Trip Settings';
        $s = new Setting();
        $setting = $s->getSetting();

        return view('admin.settings.tripsetting',compact('setting','title'));

    }
    public function maintenance_status()
    {
        echo "fdfd";
        $setting = Setting::find(1);
        $setting->maintenance_status = 1;
        $setting->update();
        return redirect('admin/settings/edit');
//        if ($setting->update()) {
//            //Session::flash('alert-success','تم تفعيل وضع الصيانة');
//            return redirect('admin/settings/edit');
//        } else {
//            //Session::flash('alert-success','لم يتم تفعيل وضع الصيانة');
//            return redirect('admin/settings/edit');
//        }
    }

    public function disactive_maintenance_status()
    {

        $setting = Setting::find(1);
        $setting->maintenance_status = 0;
        if ($setting->update()) {
            Session::flash('alert-success','تم إلغاء تفعيل وضع الصيانة');
            return redirect('admin/settings/edit');
        } else {
            Session::flash('alert-success','لم يتم إلغاء تفعيل وضع الصيانة');
            return redirect('admin/settings/edit');
        }
    }


}
