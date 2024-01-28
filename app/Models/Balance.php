<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }
    protected $fillable = [
        'renew_amount',
        'image',
        'ishaar',
        'driver_id',
        'is_renew',
        'trip_id'
    ];
    public function makeDiscount($user_id){
        $setting = new Setting();
        $s = $setting->getSetting();

        $today = Carbon::now();
        //$ba = $this::where('driver_id',$user_id)->first();

        $x = Sum_money::where('driver_id',$user_id)->latest()->first();

        $b = new Balance();
        $b->driver_id = $user_id;
        $b->renew_amount = $s->discount_driver;
        $b->ishaar = '';
        $b->is_renew = 0;//1 if renew,0 if discount, 2 if add user discount, 3 if trip, 4 if compensation
        $b->image = '';
        $b->save();

        if($x){
            $x->balance -= $s->discount_driver;
            //$x->amount -= $s->discount_driver;
            $x->save();
        }
        else{
            //add new record for this driver
            $y = new Sum_money();
            $y->balance -= $s->discount_driver;
            //$y->amount =  $y->balance;
            $y->driver_id =  $user_id;
            $y->work_day = $today->toDateString();
            $y->save();
        }

    }

    public function addBalanceRecord($driver_id, $amount, $ishaar, $is_renew, $image, $trip_id){
        $data = array();
        $data['driver_id'] = $driver_id;
        $data['renew_amount'] = $amount;
        $data['ishaar'] = $ishaar;
        $data['is_renew']  = $is_renew;//1 if renew,0 if discount, 2 if add user discount, 3 if trip, 4 if compensation
        // echo $is_renew . "-";
        $data['trip_id']  = $trip_id;
        if ($image) {
            $file = $image;
            //echo "image";
            $file_name = 'ishaar/' . md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move('storage/ishaar/', $file_name)) {
                $data['image'] = $file_name;
            }
        } else {
            $data['image'] = '';
        }
        Balance::create($data);

//            $this->driver_id = $driver_id;
//            $this->renew_amount = $amount;
//            $this->ishaar = $ishaar;
//            $this->is_renew = $is_renew;//1 if renew,0 if discount, 2 if add user discount, 3 if trip, 4 if compensation
//            echo $is_renew . "-";
//            $this->trip_id = $trip_id;
//            if ($image) {
//                $file = $image;
//                //echo "image";
//                $file_name = 'ishaar/' . md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
//                if ($file->move('storage/ishaar/', $file_name)) {
//                    $this->image = $file_name;
//                }
//            } else {
//                $this->image = '';
//            }
//
//            $this->save();

        //echo $d;

    }
}
