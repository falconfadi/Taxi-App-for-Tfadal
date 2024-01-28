<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */

    public function getLatestCounter($trip_id , $driver_id){
        $record = Counter::where('trip_id',$trip_id)->where('user_id',$driver_id)->orderBy('id','desc')->limit(1)->first();
        return $record;
    }

}
