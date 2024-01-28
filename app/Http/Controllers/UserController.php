<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\FCMService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function sendNotificationrToUser($id)
    {
        // get a user to get the fcm_token that already sent.
        //    from mobile apps
        $user = User::findOrFail($id);
        //var_dump($user);
        $data = [
            'trip_id'=> 0,
            'notification_type'=>'Ads'
        ];
        FCMService::send(
            $user->fcm_token,
            [
                'title' => 'your title',
                'body' => 'hello omar',
            ],
            [
                'message.php' => $data
            ],
        );

    }

    public function sendd()
    {
//        Http::withHeaders([
//            'Authorization' => "key={{server_key}}",
//            "Content-Type" => "application/json"
//            ])->post('https://fcm.googleapis.com/fcm/send', [
//                "registration_ids" => [array of tokens],
//                "data" => [
//            "androidChannel" => "default"
//            ],
//            "notification" => [
//            "title" => $title,
//            "body" => $body,
//            ]
//        ]);
    }
}
