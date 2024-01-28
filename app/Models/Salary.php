<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

//    public function makeDiscount($user_id){
//        $setting = new Setting();
//        $s = $setting->getSetting();
//
//        //$s->discount_driver
//        $salary = $this::where('user_id',$user_id)->first();
//        if($salary){
//            $salary->salary = 0;
//            $salary->discount += $s->discount_driver;
//            $salary->user_id = $user_id;
//            $salary->update();
//        }
//        else{
//            $this->salary = 0;
//            $this->discount = $s->discount_driver;
//            $this->user_id = $user_id;
//            $this->save();
//        }
//        //return $x;
//    }
}
