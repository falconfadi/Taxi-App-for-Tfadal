<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SendPushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Kutia\Larafirebase\Facades\Larafirebase;


class MakeNotificationController extends Controller
{
    public function index()
    {
        return view('home2');
    }
    public function form()
    {
        return view('home3');
    }
    public function updateToken(Request $request){
        try{
            //$request->user()->update(['fcm_token'=>$request->token]);
            $user_id = $request->user()->id;
            $user = User::where('id',$user_id)->first();
            $user->fcm_token = $request->token;
            $user->save();
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }

    public function notification(Request $request){
        $request->validate([
            'title'=>'required',
            'message.php'=>'required'
        ]);

        try{
            $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
            //var_dump($fcmTokens);
            Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));

            /* or */

            //auth()->user()->notify(new SendPushNotification($request->title,$request->message.php,$fcmTokens));

            /* or */

//            Larafirebase::withTitle($request->title)
//                ->withBody($request->message.php)
//                ->sendMessage($fcmTokens);

            //return redirect()->back()->with('success','Notification Sent Successfully!!');

        }catch(\Exception $e){
            report($e);
            return redirect()->back()->with('error','Something goes wrong while sending notification.');
        }
    }


}
