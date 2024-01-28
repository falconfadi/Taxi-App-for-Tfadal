<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class FCMService
{
    public static function send($token, $notification, $data)
    {

        if( isset($data['message']['add_features'])  ) {
            $notification['sound'] = "test_song.mp3";
            $notification['channel_id'] = "normal_channel";
            $notification['android_channel_id'] = "normal_channel";
            $notification["content_available"]= true;
            $notification["content-available"] = 1;
            $notification["priority"]= "5";
            $notification["mutable_content"]= true;

            $data['sound'] = "test_song.mp3";
            $data['channel_id'] = "normal_channel";
            $data['android_channel_id'] = "normal_channel";
            $data["content_available"]= true;
            $data["content-available"] = 1;
            $data["priority"]= "5";
            $data["mutable_content"]= true;

        }

        $fields = [
            "to" => $token,
            "priority" => 5,
            'notification' => $notification,
            'data' => $data,
            'vibrate' => 1,
            'sound' => 1
        ];

        if( isset($data['message']['add_features']  )){
            $fields["android"] = [
                "sound"=> "test_song.mp3",
                "channel_id"=> "normal_channel",
                "android_channel_id"=> "normal_channel",
                "notification"=> [
                    "content_available"=> true,
                    "content-available"=>1,
                    "priority "=> true,
                    "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
                    "sound"=> "test_song.mp3",
                    "channel_id"=> "normal_channel",
                    "android_channel_id"=> "normal_channel"
                ],
            ];
            $fields["apns"] = [
                "content_available"=> true,
                "content-available"=>1,
                "mutable-content"=> true,
                "sound"=> "test_song.wav",
                "payload"=> [
                    "sound"=> "test_song.wav",
                    "priority"=> "5",
                    "apns-push-type"=>"background",
                    "apns-priority"=>"5",
                    "mutable-content"=> true,
                    "aps" => [
                        "priority"=> "5",
                        "sound"=> "test_song.wav",
                        "category"=> "NEW_MESSAGE_CATEGORY",
                        "content_available"=> true,
                        "content-available"=>1,
                        "mutable-content"=> true,
                        "apns-push-type"=>"background",
                        "apns-priority"=>"5",
                        // 'content-available': 1
                    ],
                    "headers"=>[
                        "apns-push-type"=>"background",
                        "apns-priority"=>"5",
                    ]
                ]
            ];
        }

        $isDriver = $data['message'];
        if($isDriver['is_driver']==0){
            $headers = [
                'accept: application/json',
                'Content-Type: application/json',
                'Authorization: key=' . env('FIREBASE_SERVER_KEY')/*Config::get('fcm.fcm_token')//env('FIREBASE_SERVER_KEY')*/
            ];
        }else{
            $headers = [
                'accept: application/json',
                'Content-Type: application/json',
                'Authorization: key=' . env('FIREBASE_SERVER_KEY_DRIVER')
            ];
        }


        $ch = \curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
