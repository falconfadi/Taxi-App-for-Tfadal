<?php

namespace App\Http\Controllers\Api;

use App\Models\Offers;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;


class NewPasswordController extends Controller
{

    //protected $verificationCodeText = 'Verification Code: ';
    protected $verificationCodeText = 'Verification%20Code:%20';
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function forgotPasswordNoAuth(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);


        $user = User::where('phone', $request->phone)->first();
        if($user){
//            $varification_code = rand(100000, 999999);
//            $sendSms = new \App\Http\Controllers\Admin\SendSMSController();
//            $msg = $this->verificationCodeText.$varification_code;
//            $y = $sendSms->send($msg, $request->phone);
//            //save verification code
//            $c = new VerificationCode();
//            $c->saveCode($user->id,$varification_code );
            $c = new VerificationCode();
            $varification_code = $c->sendVerificationCode($user->id, $request->phone);

            return response()->json(
                [
                    'message'=>'New Password',
                    'data'=> [
                        'varification_code'=>$varification_code,
                        'arabic_error'=> '',
                        'english_error'=> '',
                        'arabic_result'=> 'تم إرسال كود التحقق',
                        'english_result'=> 'activation code successfully sent',
                    ]
                ]
            );
        }
        else{
            return response()->json(
                [   'message'=>'New Password',
                    'data'=> [
                        'arabic_error'=>'لايوجد مستخدم ',
                        'english_error'=>'no data',
                        'arabic_result'=>'',
                        'english_result'=>'',
                    ]
                ]
            );
        }
    }

    public function saveNewPasswordNoAuth(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password'=>'required'
        ]);

        //$newpassword = rand(100000, 999999);
        $user = User::where('phone', $request->phone)->first();
        if($user){
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(
                [
                    'message'=>'New Password',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>'تم تغيير كلمة المرور',
                        'english_result'=>'successfully changed password',
                    ]
                ]
            );
        }
        else{
            return response()->json(
                [
                    'message'=>'New Password',
                    'data'=> [
                        'arabic_error'=>'لايوجد مستخدم ',
                        'english_error'=>'no data',
                        'arabic_result'=>'',
                        'english_result'=>'',
                    ]
                ]
            );
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'password' => 'required'/*, 'confirmed', RulesPassword::defaults()]*/,
            'old_password'=>'required'
        ]);

        $user = User::where('id',$request->id)->first();
        if($user)
        {
            if (!(Hash::check($request->old_password, $user->password))) {
                return response()->json(
                    [
                        'message'=>'Change Password',
                        'data'=> [
                            'arabic_error'=>'لايوجد تطابق مع كلمة السر لديك',
                            'english_error'=>'wrong password !!',
                            'arabic_result'=>'',
                            'english_result'=>'',
                        ]
                    ]
                );
            }
            else{
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                $user->tokens()->delete();
                event(new PasswordReset($user));
                return response()->json(
                    [
                        'message'=>'Change Password',
                        'data'=> [
                            'arabic_error'=>'',
                            'english_error'=>'',
                            'arabic_result'=>'تم تغيير كلمة المرور',
                            'english_result'=>'successfully changed password',
                        ]
                    ]
                );
            }
        }
        else{
            return response()->json(
                [
                    'message'=>'Change Password',
                    'data'=> [
                        'arabic_error'=>'لايوجد مستخدم ',
                        'english_error'=>'no data',
                        'arabic_result'=>'',
                        'english_result'=>'' ]
                ]
            );
        }
        return response([
            'message'=> __($status)
        ], 500);
    }

    public function change_phone(Request $request)
    {
        //$request->phone = $request->new_phone;
        $request->validate([
            'new_phone' => 'required|unique:users,phone',
        ]);
        $user_id = $request->user()->id;


        $user = User::where('id',$user_id)->first();
        if($user)
        {
            $user->phone = $request->new_phone;
            $user->save();

            return response()->json(
                [
                    'message'=>'Change Phone',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>'تم تغيير رقم الموبايل',
                        'english_result'=>'successfully changed phone',
                    ]
                ]
            );
        }
        else{
            return response()->json(
                [
                    'message'=>'Change Phone',
                    'data'=> [
                        'arabic_error'=>'لايوجد مستخدم ',
                        'english_error'=>'no data',
                        'arabic_result'=>'',
                        'english_result'=>'' ]
                ]
            );
        }
        return response([
            'message'=> __($status)
        ], 500);

    }
    public function varification_code(Request $request){
        $request->validate([
            'phone' => 'required',
            'varification_code' => 'required'
        ]);
        $user = User::where('phone',$request->phone)->first();
        if($user)
        {
            $user->phone_verified_at =   Carbon::now();//date("Y-m-d H:i:s");
            $user->save();

            //check offers
            $o = new Offers();
            $off = $o->checkOffersOfNewUser($user->id);
            $offerOfNewUser =  ($off)?$off:false;

            return response()->json(
                [
                    'message'=>'Varification Code',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result' => 'تم تأكيد رقم الموبايل',
                        'english_result'=>'varification done',
                        'offerOfNewUser' => $offerOfNewUser]
                ]
            );
        }
        else{
            return response()->json(
                [
                    'message'=>'Varification Code',
                    'data'=> [
                        'arabic_error'=>'لايوجد مستخدم ',
                        'english_error'=>'no data',
                        'arabic_result'=>'',
                        'english_result'=>'' ]
                ]
            );
        }
    }



}
