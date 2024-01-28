<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use App\Models\Rate_trip;
use Illuminate\Http\Request;

class RateController extends Controller
{
    //[user]
    public function rate_trip(Request $request)
    {
        $request->validate([
            'stars' =>'required',
            'trip_id'=>'required'
        ]);
        $rate = new Rate_trip();
        $rate->user_id = $request->user()->id;
        $rate->comment = $request->comment;
        $rate->stars = $request->stars;
        $rate->trip_id = $request->trip_id;
        if($rate->save())
        {
            return response()->json(
                [
                    'message'=>'Rate Trip',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>' تم تقييم الرحلة',
                        'english_result'=>'Trip rate done',
                    ]
                ]
            );
        }
    }

    public function rate_app(Request $request)
    {
        $request->validate([
            'stars' =>'required'
        ]);
        $rate = new Rate();
        $rate->user_id = $request->user()->id;
        $rate->comment = $request->comment;
        $rate->stars = $request->stars;
        if($rate->save())
        {
            return response()->json(
                [
                    'message'=>'Rate App',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>' تم تقييم التطبيق',
                        'english_result'=>'app rate done',
                    ]
                ]
            );
        }
    }
}
