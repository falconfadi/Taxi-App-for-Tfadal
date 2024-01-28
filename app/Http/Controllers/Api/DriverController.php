<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Car;
use App\Models\Car_model;
use App\Models\Coordinate;
use App\Models\Driver;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Sum_money;
use App\Models\Trip;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    protected $verificationCodeText = 'Verification%20Code:%20';
    //protected $verificationCodeText = 'Verification Code: ';
    public function __construct()
    {
        parent::__construct();
    }
    public function register(Request $request)
    {
        $messages = [
            'phone.unique' => 'رقم الهاتف مسجل مسبقاً الرجاء تسجيل الدخولً',
            'image.mimes' => 'خطأ في نمط الصورة',
        /*    'car_image.mimes' => 'خطأ في نمط الصورة',
            'personal_id_image.mimes' => 'خطأ في نمط الصورة',
            'back_personal_id_image.mimes' => 'خطأ في نمط الصورة',*/

            'image.max' => 'الرجاء التأكد من أن حجم الصورة المرفقة لايتجاوز 2 ميغابايت',
        /*    'car_image.max' => 'الرجاء التأكد من أن حجم الصورة المرفقة لايتجاوز 2 ميغابايت',
            'personal_id_image.max' => 'الرجاء التأكد من أن حجم الصورة المرفقة لايتجاوز 2 ميغابايت',
            'back_personal_id_image.max' => 'الرجاء التأكد من أن حجم الصورة المرفقة لايتجاوز 2 ميغابايت',*/
        ];
        $validator = Validator::make($request->all(), [
            'phone' =>'required|unique:users',
            'name' => 'required|string|max:255',
            /*'email' => 'required|string|max:255|',*/
            'password' => ['required'],
            'image'=> 'mimes:png,jpg|max:2048',
         /*   'personal_id_image'=> 'required|mimes:png,jpg|max:2048',
            'car_image'=> 'required|mimes:png,jpg|max:2048',
            'back_personal_id_image' => 'required|mimes:png,jpg|max:2048',*/

        ],$messages );

        if ($validator->fails())
        {
            return response()->json(
                [
                    'message'=>'Driver Register',
                    'data'=> [
                        'unique'=> str_contains($validator->errors() ,'phone')?$messages['phone.unique']:'',
                        'max'=> str_contains($validator->errors() ,'image')?$messages['image.max']:'',
                        'english_error'=>'This Data is not valid',
                    ]
                ]
            );
        }


        $all_save = false;
        //return DB::transaction(function()use ($request) {

            $driver_image = 'users/avatar.png';
            if ($file = $request->file('image')) {
                //store file into document folder
                $driver_image = $file->store('public/drivers');
                $driver_image = str_replace('public/', '', $driver_image);
            }
            $user = User::create([
                'name' => $request->name,
                'email'=>$request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                /*'address' => $request->address,*/
                'image' => $driver_image,
                'device_token'=> $request->device_token,
                'fcm_token' =>$request->firebase_token,
                'latitude'  => $request->latitude,
                'longitude'  => $request->longitude,
                'is_driver'=>1,
                'ip' => \Request::ip(),
                'gender' =>($request->gender)? $request->gender:0,
                'operation_system_version' => $request->operation_system_version ,
                'system_type' => $request->system_type
            ]);

            event(new Registered($user));
            $token = $user->createToken('authtoken');
            $personal_id_image ='users/avatar.png';

            $back_personal_id_image ='users/avatar.png';

            $not_convicted_image ='users/avatar.png';

            $driver = new Driver();
            $driver->birthdate = $request->birthdate;
            $driver->last_name = $request->last_name;
            $driver->father_name = $request->father_name;
            $driver->region_id = $request->region_id;
            $driver->marital_status = $request->marital_status;
            $driver->personal_id_image = $personal_id_image;
            $driver->back_personal_id_image = $back_personal_id_image;
            $driver->not_convicted_image = $not_convicted_image;
            $driver->national_id = ($request->national_id)? $request->national_id:'';
            $driver->user_id = $user->id;
            $driver->phone = $request->phone;
            $driver->save();

            $car = New Car();
            $car->image = 'users/avatar.png';
            $car->driver_id = $user->id;
            $car->car_type = $request->car_type_id;
            $car->passenger_number = $request->passenger_number;
            $car->plate = $request->plate;
            $car->year = $request->year;
            $car->mark = $request->brand;
            $car->plate_city_id = $request->plate_city_id;
            $car->color_id = $request->color_id;

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
            $car->save();


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
            $body = $welcomeMsg;
            $title = "مرحباً بكم";
            $notificationObj->sendNotifications($user->id,$title , $body, $data);

            $x = new  Notification();
            $x->saveNotification([$user->id],$title,2, $body, 1,0);

            return response()->json(
                [
                    'message'=>'Driver Registered',
                    'data'=> ['token' => $token->plainTextToken,
                        'varification_code'=>$varification_code,
                        'driver' => $user ,
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>' تم إضافة سائق جديد',
                        'english_result'=>'success to register',
                    ],
                    'unique'=>'',
                    'max'=>''
                ]
            );
       // });
    }

    public function driver_login(LoginRequest $request)
    {
        $r = $request->authenticate();
        //if auth failed
        if(is_array($r)) {
            return response()->json($r);
        }
        $user_id = $request->user()->id;
        $user = User::where('id',$user_id)->where('is_driver',1)->first();

        $token = $request->user()->createToken('authtoken');


        if($user){
            if($user->device_token==$request->device_token || $user->device_token=='')
            {
                if(is_null($request->user()->phone_verified_at))
                {

                    $c = new VerificationCode();
                    $varification_code = $c->sendVerificationCode($user->id, $user->phone);

                    return response()->json(
                        [
                            'message'=>'Driver Login',
                            'data'=> [
                                'user'=> $request->user(),
                                'token'=> $token->plainTextToken,
                                'arabic_error'=>'',
                                'english_error'=>'',
                                'arabic_result'=>'تم إرسال كود التحقق بنجاح',
                                'english_result'=>'Verification Code sent successfully',
                                'verified'=>0,
                                'varification_code' => $varification_code
                            ]
                        ]
                    );
                }
                $user->fcm_token = $request->firebase_token;
                $user->ip = \Request::ip();
                if($user->device_token=='' || !$user->device_token) $user->device_token=$request->device_token;
                $user->save();
                return response()->json(
                    [
                        'message'=>'Logged in',
                        'data'=> [
                            'user'=> $request->user(),
                            'token'=> $token->plainTextToken,
                            'arabic_error'=>'',
                            'english_error'=>'',
                            'arabic_result'=>' تم تسجيل الدخول',
                            'english_result'=>'success to logged in',
                            'verified'=>1,
                            'varification_code' => 0
                        ]
                    ]
                );
            }
            else{
                return response()->json([
                    'message'=>'Not Logged in',
                    'data'=> [
                        'arabic_error'=>'يمنع تسجيل الدخول من جهاز آخر',
                        'english_error'=> "you can not login from other device",
                        'arabic_result'=>'',
                        'english_result'=>'',
                    ]
                ]);
            }
        }else{
            return response()->json([
                'message'=>'Not Logged in',
                'data'=> [
                    'arabic_error'=>'بيانات خاطئة',
                    'english_error'=> __('auth.failed'),
                    'arabic_result'=>'',
                    'english_result'=>'',
                ]
            ]);
        }
    }
    public function update_location_api(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        $user = User::find($request->user_id);
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;

        //add location to the coordinates
        $coordinates = new Coordinate();
        $coordinates->latitude = $request->latitude;
        $coordinates->longitude = $request->longitude;
        $coordinates->driver_id = $request->user_id;
        $coordinates->trip_id = $request->trip_id;
        $coordinates->save();

        if ($user->save())
        {
            return response()->json([
                "success" => true,
                "message" => "successfully updated",
                'data'=> [
                    'arabic_error'=>'',
                    'english_error'=>'',
                    'arabic_result'=>'تم تعديل المكان',
                    'english_result'=>'Successfully updated',
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لم يتم تعديل الموقع',
                    'english_error' => 'location Not updated!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

    public function update(Request $request)
    {
        $messages = [
            'image.mimes' => 'خطأ في نمط الصورة',
           /* 'personal_id_image.mimes' => 'خطأ في نمط الصورة',
            'back_personal_id_image.mimes' => 'خطأ في نمط الصورة',*/
            'image.max' => 'الرجاء التأكد من أن حجم الصورة المرفقة لايتجاوز 2 ميغابايت',
           /* 'car_image.max' => 'الرجاء التأكد من أن حجم الصورة المرفقة لايتجاوز 2 ميغابايت',
            'personal_id_image.max' => 'الرجاء التأكد من أن حجم الصورة المرفقة لايتجاوز 2 ميغابايت',
            'back_personal_id_image.max' => 'الرجاء التأكد من أن حجم الصورة المرفقة لايتجاوز 2 ميغابايت',*/
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image'=> 'mimes:png,jpg|max:2048',
          /*  'personal_id_image'=> 'mimes:png,jpg|max:2048',
            'back_personal_id_image' => 'mimes:png,jpg|max:2048',*/
        ],$messages );

        if ($validator->fails())
        {
            return response()->json(
                [
                    'message'=>'Driver Register',
                    'data'=> [
                        'max'=> str_contains($validator->errors() ,'image')?$messages['image.max']:'',
                        'english_error'=>'This Data is not valid',
                    ]
                ]
            );
        }
        $user = User::find($request->driver_id);
        if ($file = $request->file('image')) {
            //store file into document folder
            $image = $file->store('public/users');
            $image = str_replace("public/", "", $image);
            $user->image = $image;
        }
        $user->name = $request->name;
        if ($user->save())
        {
            $driver = Driver::where('user_id',$user->id)->first();

            $driver->father_name = $request->father_name;
            $driver->last_name = $request->last_name;
            $driver->birthdate = $request->birthdate;
            $driver->marital_status = $request->marital_status;
            $driver->region_id = $request->region_id;
            $driver->update();
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

    public function changeOnlineStatus(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
           'is_connected' =>'required'
        ]);
        $set = new Setting();
        $setting = $set->getSetting();
        //$user = User::find($request->driver_id);
        $driver = Driver::where('user_id',$request->driver_id)->first();
        if ($driver)
        {
            if($driver->verified==0){
                return response()->json([
                    "success" => true,
                    "message" => "Not updated",
                    'data'=> [
                        'arabic_error'=>'الكابتن غير مثبت',
                        'english_error'=>'Captain is not Verified',
                        'arabic_result'=> '',
                        'english_result'=> '',
                    ]
                ]);
            }else{
                $driver->is_connected = $request->is_connected;
                $driver->save();
                return response()->json([
                    "success" => true,
                    "message" => "successfully updated",
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=> $setting->connected_message_arabic,
                        'english_result'=> $setting->connected_message_english,
                    ]
                ]);
            }
        } else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => 'Not updated!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }


    //get balance and amount
//    public function getBalancePerDay($driver_id){
//        $today = Carbon::now();
//        $x = Sum_money::where('driver_id',$driver_id)->where('work_day', $today->toDateString())->first();
//        $s = new Setting();
//        $max_amount_to_stop_driver = $s->getSetting()->max_amount_to_stop_driver;
//        if($x){
//                return response()->json([
//                    "success" => true,
//                    'data'=> [
//                        'balance'=>$x->amount,
//                        'max_amount_to_stop_driver'=>$max_amount_to_stop_driver,
//                    ]
//                ]);
//        }else{
//            return response()->json([
//                "success" => false,
//                "message" => "successfully updated",
//                'data'=> [
//                    'arabic_error'=>'لاتوجد بيانات',
//                    'english_error'=>'no data',
//
//                ]
//            ]);
//        }
//
//    }




}
