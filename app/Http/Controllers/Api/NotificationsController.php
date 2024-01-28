<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function sendNotifications($id,$title,$body,$notification)
    {
        // get a user to get the fcm_token that already sent.
        //    from mobile apps
        $user = User::findOrFail($id);
        $notification['title'] = $title;
        $notification['body'] = $body;
        $notification['driver_id']= $id;
        $data = array();



        if($notification['notification_type']=='New Trip'){

        }else{
            $data = ['title' => $title, 'body' => $body  ];
        }
        FCMService::send(
            $user->fcm_token,
            $data,
            [
                'message' => $notification
            ],
        );
    }
    public function getNotificationsToUser(Request $request)
    {
        $request->validate([
            'user_id' =>'required'
        ]);
        $notifi = new Notification();
        $notifications = $notifi->getNotificationByUserId($request->user_id);//Notification::where('user_id',$request->user_id)->get();
        if($notifications){
            return response()->json(
                [
                'message'=>'notifications',
                    'data'=> [
                    'notifications'=>$notifications,
                    ]
                ]
            );
        }
        else{
            return response()->json(
                [
                    'message'=>'notifications',
                    'data'=> [
                        'arabic_error'=>'لا يوجد لديك إشعارات',
                        'english_error'=>'No notifications',
                        'arabic_result'=>'',
                        'english_result'=>'',
                    ]
                ]
            );
        }
    }


    public function deleteNotificationById(Request $request)
    {
        $request->validate([
            'user_id' =>'required',
            'notification_id'=>'required'
        ]);
        $n = Notification::where('id',$request->notification_id)->first();
        $user = User::find($request->user_id);

            if($n->is_all ==0) {
                $user->notifications()->detach([$n->id]);
                $count = Notification::where('id',$request->notification_id)->delete();
            }else{
                $user->notifications()->detach([$n->id]);
            }
            return response()->json([
                'message'=>'Successfully Deleted',
                'data'=> [
                    'arabic_error'=>'',
                    'english_error'=>'',
                    'arabic_result'=>' تم حذف الاشعار ',
                    'english_result'=>'Success to delete a notification',
                ]
            ]);

//        else{
//            return response()->json([
//                'message'=>'Delete Failed',
//                'data'=> [
//                    'arabic_error'=>'لم يتم حذف الإشعار',
//                    'english_error'=>'Notification not deleted',
//                    'arabic_result'=>'',
//                    'english_result'=>'',
//                ]
//            ]);
//        }

    }
    public function deleteAllNotifications(Request $request)
    {
        $request->validate([
            'user_id' =>'required'
        ]);
        //$count = Notification::where('user_id',$request->user_id)->delete();
        $notifi = new Notification();
        $notifications = $notifi->getNotificationByUserId($request->user_id);
        $user = User::find($request->user_id);
        if(count($notifications)  ){

            foreach ($notifications as $n){
                if($n->is_all ==0){
                    $user->notifications()->detach([$n->id]);
                    $n->delete();
                }else{
                    $user->notifications()->detach([$n->id]);
                }
                //$user->notifications()->detach([]);
            }
            return response()->json([
                'message'=>'Successfully Deleted',
                'data'=> [
                    'arabic_error'=>'',
                    'english_error'=>'',
                    'arabic_result'=>' تم حذف الاشعارات ',
                    'english_result'=>'Success to delete all notification',
                ]
            ]);
        }
        else{
            return response()->json([
                'message'=>'Delete Failed',
                'data'=> [
                    'arabic_error'=>'لم يتم حذف الإشعارات',
                    'english_error'=>'Notifications not deleted',
                    'arabic_result'=>'',
                    'english_result'=>'',
                ]
            ]);
        }
    }

    public function sendInvoiceNotification($totalPrice, $trip, $expectedDuration, $tripDistance, $price, $discount){
        $data = [
                'trip_id'=> $trip->id,
                'notification_type'=>'trip',
                'is_driver' => 0,
                'time' => round($expectedDuration, 2),
                'distance'=>round($tripDistance, 2),
                'price_before_discount' => $totalPrice ,
                'net_price'=>$price,
                'discount' =>$discount,
            ];

            $title = "فاتورة الرحلة";
            $body = "<h2>";
            $body .= "تفاصيل الفاتورة ";
            $body .= "<br>";
            $body .= " القيمة الكلية:  ".$totalPrice;
            $body .= "<br>";
            $body .= " المسافة المقطوعة:  ".round($tripDistance, 2);
            $body .= "<br>";
            $body .= " الحسومات:  ".$discount;
            $body .= "<br>";
            $body .= " المبلغ الصافي:  ".$price;
            $body .= "</h2>";
            $this->sendNotifications($trip->user_id, $title, $body, $data);
            $x = new  Notification();
            $x->saveNotification([$trip->user_id],$title,2, $body,0,0);

    }


}
