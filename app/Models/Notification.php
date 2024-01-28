<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class,'notifications_users','notification_id','user_id' );
    }

    public function saveNotification($users, $title, $notificationType ,$body,$is_driver,$is_all)
    {
        $this->title = ($title)?$title:'';
        $this->body = $body;
        $this->notification_type = $notificationType; //1=>advertisement, 2 =>trip
        $this->is_driver = $is_driver;
        $this->is_all = $is_all;
        if ($this->save()) {
            $this->users()->attach($users);
            //$this->is_driver = $is_driver;
        }
    }

    public function getNotificationByUserType($is_driver){
        $notifications = Notification::where('is_driver',$is_driver)->with('users')->get();
        return $notifications;
    }

    public function getNotificationByUserId($user_id){
        $user = User::find($user_id);
        $notifications = Notification::whereHas('users',function($subQ) use($user_id){
            $subQ->where('user_id',$user_id);
        })
            //notifications to all
        ->orWhere(function($q) use($user) {
            if($user->is_driver==1) {
                $q->where('is_all',1)->where('is_driver',1);
            }else{
                $q->where('is_all',1)->where('is_driver',0);
            }
        })
        ->where('notification_type',1)
        ->with('users')->orderBy('id','DESC')->take(10)->get();

        return $notifications;
    }
}
