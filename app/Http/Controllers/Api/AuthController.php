<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FailResource;
use App\Models\Car;
use App\Models\Complaint;
use App\Models\Driver;
use App\Models\FarwayRegion;
use App\Models\FreezeReason;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Policy;
use App\Models\Promotion;
use App\Models\Rate;
use App\Models\Setting;
use App\Models\SharedTrip;
use App\Models\Trip;
use App\Models\TripSetting;
use App\Models\VerificationCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

   // protected $verificationCodeText = 'Verification Code: ';
    //protected $verificationCodeText = 'Verification%20Code:%20';
    public function register(Request $request)
    {
        $messages = [
            'phone.unique' => 'رقم الهاتف مسجل مسبقاً الرجاء تسجيل الدخولً',
            'name.string' => 'الاسم يجب أن يكون سلسلة محارف '
        ];
        $validator = Validator::make($request->all(), [
            'phone' => 'required|unique:users',
            'name' => 'required|string|max:255',
            /* 'email' => 'required|string|email|max:255|',*/
            'password' => ['required'],
        ], $messages);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => 'User Register',
                    'data' => [
                        'required' => 'كل الحقول مطلوبة',
                        'required' => 'all fields required',
                        'unique' => str_contains($validator->errors(), 'phone') ? $messages['phone.unique'] : '',
                        'string' => str_contains($validator->errors(), 'name') ? $messages['name.string'] : '',
                    ]
                ]
            );
        } else {

            $user = User::create([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => 'users/avatar.png',
                'device_token' => $request->device_token,
                'fcm_token' => $request->firebase_token,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'ip' => \Request::ip(),
                'gender' => ($request->gender) ? $request->gender : 0,
                'operation_system_version' => $request->operation_system_version ,
                'system_type' => $request->system_type
            ]);

            event(new Registered($user));

            $token = $user->createToken('authtoken');

            $location = new Location();
            $location->latitude = $request->latitude;
            $location->longitude = $request->longitude;
            $location->title = $request->place_name;
            $location->user_id = $user->id;
            $location->place_type = 1;
            $x = $location->save();

            $c = new VerificationCode();
            $varification_code = $c->sendVerificationCode($user->id, $request->phone);

            //send welcome notification
            $s = new Setting();
            $welcomeMsg = ($s->getSetting()->registration_welcome_msg_ar)?$s->getSetting()->registration_welcome_msg_ar:'';
            $notificationObj = new NotificationsController();
            $data = [
                'trip_id' => 0,
                'notification_type' => 'advertisement',
                'is_multiple' => 0,
                'is_driver' => 0
            ];
            $title = "مرحباً بكم";
            $body = $welcomeMsg;
            $notificationObj->sendNotifications($user->id, $title, $body, $data);
            $x = new  Notification();
            $x->saveNotification([$user->id],$title,1,$body, 0,0);
            return response()->json(
                ['message' => 'User Registered',
                    'data' => ['token' => $token->plainTextToken,
                        'varification_code' => $varification_code,
                        'user' => $user,
                        'arabic_error' => '',
                        'english_error' => '',
                        'arabic_result' => ' تم إضافة مستخدم جديد',
                        'english_result' => 'success to register']
                ]
            );
        }
    }

    //[user , driver ]
    public function getUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);
        $user = User::find($request->user_id);
        if ($user) {
            if ($user->is_driver == 1) {
//                $user = Driver::where('user_id',$request->user_id)->whereHas('region',function($subQ) {
//                    $subQ->with('city');
//                })
//                    ->with('driver_as_user','region')->first();
                $user = Driver::where('user_id', $request->user_id)
                    ->with(['driver_as_user', 'region' => function ($query) {
                        $query->with('city');
                    }])->first();
                $car = Car::with('carType', 'carModel', 'brand', 'city')->where('driver_id', $request->user_id)->first();
                return response()->json([
                    "success" => true,
                    "message" => "user",
                    'data' => [
                        'user' => $user,
                        'car' => $car
                    ]
                ]);
            }
            return response()->json([
                "success" => true,
                "message" => "user",
                'data' => [
                    'user' => $user
                ]
            ]);
        } else {
            return response()->json([
                "success" => true,
                "message" => "user",
                'data' => [
                    'arabic_error' => '',
                    'english_error' => '',
                    'arabic_result' => 'تم إضافة رحلة ',
                    'english_result' => 'successfully added',
                    'trip_id' => ''
                ]
            ]);
        }
    }

    public function login(LoginRequest $request)
    {
        $r = $request->authenticate();
        //if auth failed
        if (is_array($r)) {
            return response()->json($r);
        }
        $user_id = $request->user()->id;
        $user = User::where('id', $user_id)->where('is_driver', 0)->first();
        $token = $request->user()->createToken('authtoken');

        if ($user) {
            if ($user->device_token == $request->device_token || $user->device_token == '') {

                if (is_null($request->user()->phone_verified_at)) {

                    $c = new VerificationCode();
                    $varification_code = $c->sendVerificationCode($user->id, $request->phone);
                    return response()->json(
                        [
                            'message' => 'Login',
                            'data' => [
                                'user' => $request->user(),
                                'token' => $token->plainTextToken,
                                'arabic_error' => '',
                                'english_error' => '',
                                'verified' => 0,
                                'varification_code' => $varification_code
                            ]
                        ]
                    );
                }
                $user->ip = \Request::ip();
                $user->fcm_token = $request->firebase_token;
                if ($user->device_token == '' || !$user->device_token) $user->device_token = $request->device_token;
                $user->save();
                return response()->json(
                    [
                        'message' => 'Logged in',
                        'data' => [
                            'user' => $request->user(),
                            'token' => $token->plainTextToken,
                            'arabic_error' => '',
                            'english_error' => '',
                            'arabic_result' => ' تم تسجيل الدخول',
                            'english_result' => 'success to logged in',
                            'verified' => 1,
                            'varification_code' => 0
                        ]
                    ]
                );
            } else {
//                return response()->json([
//                    'message' => 'Not Logged in',
//                    'data' => [
//                        'arabic_error' => 'يمنع تسجيل الدخول من جهاز آخر',
//                        'english_error' => "you can not login from other device",
//                        'arabic_result' => '',
//                        'english_result' => '',
//                    ]
//                ]);
                $failResource = new FailResource('Not Logged in',"you can not login from other device",'يمنع تسجيل الدخول من جهاز آخر');
                return response()->json($failResource );
            }
        } else {
//            return response()->json([
//                'message' => 'Not Logged in',
//                'data' => [
//                    'arabic_error' => 'بيانات خاطئة',
//                    'english_error' => __('auth.failed'),
//                    'arabic_result' => '',
//                    'english_result' => '',
//                ]
//            ]);
            $failResource = new FailResource('Not Logged in',__('auth.failed'),'بيانات خاطئة');
            return response()->json($failResource );
        }
    }

    public function logout(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);
        $user = User::find($request->user_id);
        if ($user->is_driver == 1) {
            $driver = Driver::where('user_id', $user->id)->first();
            $driver->is_connected = 0;
            $driver->update();
        }
        $user->device_token = '';
        $user->save();
        $request->user()->tokens()->delete();

        return response()->json(
            [
                'message' => 'Logged out'
            ]
        );
    }

    public function settings(Request $request)
    {
        $settings = Setting::find(1);
        $message = '';
        $message_en = '';
        $tripsSetting = TripSetting::find(1);
        if ( $request->user_id != 0) {
            $user = User::find($request->user_id);
            //$user->id = $request->user_id;
            if($user)
            {
                if(!(is_null($request->firebase_token )|| $request->firebase_token == '' ))
                {
                    $user->fcm_token = $request->firebase_token;
                    $user->save();
                }
                //SEND FREEZING STATUS

                //get freeze reason
                //get latest freeze record
                $freezeReason = FreezeReason::where('user_id', $request->user_id)->orderBy('id', 'desc')->limit(1)->first();
                if($freezeReason){
                    if($freezeReason->is_freeze==1){
                        $message = $freezeReason->reason;
                        $message_en = $freezeReason->reason;
                    }else{
                        $message = '';
                        $message_en = '';
                    }
                }else{
                    $message = '';
                    $message_en = '';
                }
            }
        }
        $policy = Policy::find(1);
        $policySTR = $policy->arabic_privacy." ".$policy->english_privacy;

        return response()->json(
            [
                'message' => 'settings',
                'data' => [
                    'email' => $settings->email,
                    'phone' => $settings->phone,
                    'facebook' => $settings->facebook,
                    'whatsapp_number' => $settings->whatsapp_number,
                    'android_version' => $settings->android_version,
                    'ios_version' => $settings->ios_version,
                    'ios_app_url' => $settings->ios_app_url,

                    'english_currency' => $settings->english_currency,
                    'arabic_currency' => $settings->arabic_currency,

                    'twitter_link' => $settings->twitter_link,
                    'instagram_link' => $settings->instagram_link,
                    'youtube_link' => $settings->youtube_link,
                    'site_url' => $settings->site_url,
                    'time_to_request_driver_destination' => $settings->time_to_request_driver_destination,
                    'is_enabled_points' => $settings->is_enabled_points,
                    'discount_driver' => $settings->discount_driver,
                    'company_percentage' => $settings->company_percentage,
                    'enable_payment' => $settings->enable_payment,
                    'enable_PIN' => $settings->enable_PIN,
                    'connected_message_arabic' => $settings->connected_message_arabic,
                    'connected_message_english' => $settings->connected_message_english,
                    'welcome_message_arabic' => $settings->welcome_message_arabic,
                    'welcome_message_english' => $settings->welcome_message_english,
                    'first_circle_radius' => $settings->first_circle_radius,
                    'other_circles_ratio' => $settings->other_circles_ratio,
                    'time_to_move_to_other_circle' => $settings->time_to_move_to_other_circle,

                    'driver_accept_trip_timeout' => $settings->driver_accept_time_out,
                    'message_after_approve_trip_arabic' => $settings->message_after_approve_trip_arabic,
                    'message_after_approve_trip_english' => $settings->message_after_approve_trip_english,
                    'driver_circle' => $settings->driver_circle,
                    'price_open' => $settings->price_open,
                    'time_to_refresh_counter' => $settings->time_to_refresh_counter,

                    'freeze' => $message,
                    'freeze_en' => $message_en,
                    //the balance of driver
                    'max_amount_to_stop_driver' => $settings->max_amount_to_stop_driver,
                    'min_amount_to_stop_driver' => $settings->min_amount_to_stop_driver,
                    'bye_message_english' => $settings->bye_message_english,
                    'bye_message_arabic' => $settings->bye_message_arabic,
                    'alert_balance_arabic' => $settings->alert_balance_arabic,
                    'alert_balance_english' => $settings->alert_balance_english,
                    'maintenance_status' => $settings->maintenance_status,
                    //to show option of furniture
                    'is_show_furniture' => $settings->is_show_furniture,

                    'ios_app_url_driver' => $settings->ios_app_url_driver,
                    'ios_version_driver' => $settings->ios_version_driver,
                    'android_version_driver' => $settings->android_version_driver,

                    'ask_sms_ios' => $settings->ask_sms_ios,
                    'ask_sms_android' => $settings->ask_sms_android,
                    'ask_sms_ios_drivers' => $settings->ask_sms_ios_drivers,
                    'ask_sms_android_drivers' => $settings->ask_sms_android_drivers,
                    'sos_number' => $settings->sos_number,
                    'sos_number_2' => $settings->sos_number_2,

                    'time_to_update_driver_counter' => $settings->time_to_update_driver_counter,
                    'time_to_update_customer_counter' => $settings->time_to_update_customer_counter,
                    'time_to_request_driver_location' => $settings->time_to_request_driver_location,
                    'time_to_request_customer_location' => $settings->time_to_request_customer_location,
                    'request_distance_from_application' => $settings->request_distance_from_application,
                    'time_to_update_marker_on_the_map' => $settings->time_to_update_marker_on_the_map,
                    'refresh_captin_marker_when_waiting' => $settings->refresh_captin_marker_when_waiting,
                    'policy' => $policySTR,

                    'schedule_trip_value' => $tripsSetting->schedule_trip_value,
                    'schedule_trip_discount' => $tripsSetting->schedule_trip_discount,


                ]
            ]
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'mimes:png,jpg|max:2048',
        ]);

        if ($file = $request->file('image')) {

            //store file into document folder
            $image = $file->store('public/users');
            $image = str_replace("public/", "", $image);

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->image = $image;

            if ($user->save()) {
                return response()->json([
                    "success" => true,
                    "message" => "successfully uploaded",
                    'data' => [
                        'arabic_error' => '',
                        'english_error' => '',
                        'arabic_result' => 'تم تعديل البيانات',
                        'english_result' => 'successfully uploaded',
                    ]
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    'data' => [
                        'arabic_error' => 'لم يتم تعديل البيانات',
                        'english_error' => 'user Not updated!!',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
            }
        } else {
            $user = User::find($request->id);
            $user->name = $request->name;

            if ($user->save()) {
                return response()->json([
                    "success" => true,
                    "message" => "successfully updated",
                    'data' => [
                        'arabic_error' => '',
                        'english_error' => '',
                        'arabic_result' => 'تم تعديل البيانات',
                        'english_result' => 'successfully uploaded',
                    ]
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    'data' => [
                        'arabic_error' => 'لم يتم تعديل البيانات',
                        'english_error' => 'user Not updated!!',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
            }
        }
    }

    public function checkUser(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            if ($request->user_id == $user->id) {
                return response()->json([
                    "success" => false,
                    'data' => [
                        'arabic_error' => 'هذا رقم هاتفك نفسه !!',
                        'english_error' => 'please change phone number!',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
            } else {
                return response()->json([
                    "success" => true,
                    "message
                    " => "user",
                    'data' => [
                        'user' => $user,
                    ]
                ]);
            }
        } else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => ' No data!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function checkUserAndShareTrip(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'trip_id' => 'required'
        ]);
        $receiverUser = User::where('phone', $request->phone)->first();
        if ($receiverUser) {
            if ($request->user_id == $receiverUser->id) {
                return response()->json([
                    "success" => false,
                    'data' => [
                        'arabic_error' => 'هذا رقم هاتفك نفسه !!',
                        'english_error' => 'please change phone number!',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
            } else {
                //send notification
                //share his trip
                $this->shareTripAndSendNotification($request->user_id, $receiverUser->id, $request->trip_id);

                return response()->json([
                    "success" => true,
                    "message" => "user",
                    'data' => [
                        'arabic_error' => '',
                        'english_error' => '',
                        'arabic_result' => 'تمت مشاركة الرحلة بنجاح',
                        'english_result' => 'A trip shared successfully',
                    ]
                ]);
            }
        } else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => ' No data!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function shareTripAndSendNotification($originalUserId, $receiverUserId, $tripId)
    {
        $trip = Trip::find($tripId);
        $notificationObj = new NotificationsController();
        $data = [
            'trip_id' => 0,
            'notification_type' => 'advertisement',
            'is_multiple' => $trip->is_multiple,
            'is_driver' => 0
        ];
        $originalUser = User::find($originalUserId);

        $title = "مشاركة الرحلة";
        $body = " شارك الرحلة معك " . $originalUser->name;

        $notificationObj->sendNotifications($receiverUserId, $title, $body, $data);
        $x = new  Notification();
        $x->saveNotification([$receiverUserId], $title, 2,$body, 0,0);
        //save shared trip in DB
        $x = new SharedTrip();
        $x->sender_share = $originalUserId;
        $x->receiver_share = $receiverUserId;
        $x->trip_id = $tripId;
        $x->save();

        $trip->is_shared = 1;
        $trip->update();
    }

    public function sendNewVerificationCode(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user){
            $c = new VerificationCode();
            $varification_code = $c->sendVerificationCode($user->id, $user->phone);
            return response()->json(
                [
                    'message' => 'Send New Verification Code',
                    'data' => [
                        'arabic_result' => 'تم ارسال كود التحقق بنجاح',
                        'english_result' => 'Verification code sent successfully',
                        'arabic_error' => "",
                        'english_error' => "",
                        'varification_code' => $varification_code
                    ]
                ]
            );
        }
    }

    public function test()
    {
//        $user = Driver::where('user_id',128)
//            ->with(['driver_as_user','region' => function ($query){
//                $query->with('city');
//            }])->first();
//        $car = Car::with('carType','carModel','brand','city')->where('driver_id',128)->first();


//
//        $range = FarwayRegion::where('from','<=',$extraDistance)->where('to','>=',$extraDistance)->first();
//        if ($range)
//            echo $range->price;
//        else
//            echo 0;

//        $r = new FarwayRegion();
//        $extraPrice = $r->getRange($extraDistance);
//        echo $extraPrice;


    }
}

