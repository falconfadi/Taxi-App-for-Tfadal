<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use stdClass;

class NotificationsController extends Controller
{
    public function index()
    {
        $title = __('menus.users_notifications');

        $U = new User();
        $users = $U->getAllUsers();
        //$drivers = $U->getAllDrivers();
        $all = $U->all();
        $x = new Notification();
        $notifications = $x->getNotificationByUserType(0);

        $names = array();
        foreach ($all as $name){
            $names[$name->id] = $name->name;
        }
        return view('admin.notifications.index',compact('title','notifications','users','names'));
    }

    public function driversNotifications()
    {
        $title = __('menus.drivers_notifications');

        $U = new User();
        $drivers = $U->getAllDrivers();
        $all = $U->all();
        $x = new Notification();
        $notifications = $x->getNotificationByUserType(1);
//        echo "<pre>";
//         print_r($notifications);exit();
//        echo "</pre>";exit();

        return view('admin.notifications.driversNotifications',compact('title','notifications','drivers'));
    }

    //send notifications manually to users
    public function sendNotification(Request $request)
    {
        //all users
        if($request->input('all')){
            $u = new User();
            $userIds = $u->getUsersIds();
        }
        else{
            $userIds  = $request->input('users');
        }
        if(!empty($userIds)){
            $this->sendNotifications(0,$request, $userIds);
            Session::flash('alert-success','تم إرسال الإشعار للعملاء');
            return redirect('admin/notifications');
        }else{
            Session::flash('alert-danger','لم يتم إرسال الإشعار للعميل');
            return redirect('admin/notifications');
        }
    }

    //send notifications manually
    public function sendNotificationDrivers(Request $request)
    {

        if($request->input('all')){
            $u = new User();
            $userIds = $u->getDriversIds();
            $is_all = 1;
        }
        else{
            $userIds  = $request->input('drivers');
            $is_all = 0;

        }
        if(!empty($userIds)){
            $this->sendNotifications(1,$request, $userIds,$is_all);
            Session::flash('alert-success','تم إرسال الإشعار للكباتن');
            return redirect('admin/drivers_notifications');
        }else{
            Session::flash('alert-danger','لم يتم إرسال الإشعار ');
            return redirect('admin/drivers_notifications');
        }
    }

    public function sendNotifications($is_driver,$request, $userIds,$is_all=0){

        $notificationObj = new \App\Http\Controllers\Api\NotificationsController();
        $data = [
            'trip_id'=> 0,
            'notification_type'=>'advertisement',
            'is_driver' =>$is_driver
        ];
        for($i=0;$i<count($userIds);$i++){
            $notificationObj->sendNotifications($userIds[$i], $request->title, $request->body, $data);
        }
        $notification = new Notification();
        $notification->saveNotification($userIds, $request->title,1, $request->body, $is_driver,$is_all);
    }
    public function destroy($id)
    {
        $res = Notification::find($id)->delete();
//        if ($res){
//            Session::flash('alert-success','Offer Deleted !!');
//            return redirect('admin/offers');
//        }
        return back()->with('success','تم حذف الإشعار بنجاح');
    }

    public function test(){
        $notification = new Notification();
        $req =    new stdClass();
        $req->title = "hll";
        $req->body = "rtrtr";
        $req->is_all = 1;
        $notification->saveNotification([347], $req,1,  1);
//        $notifi = new Notification();
//        $notifications = $notifi->getNotificationByUserId(345);
////        echo "<pre>";
////         print_r($notifications);exit();
////        echo "</pre>";exit();
//        foreach ($notifications as $no){
//            echo $no->titlo;echo "-";
//        }
    }
}
