<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DriverAlert;
use App\Models\User;
use Illuminate\Http\Request;

class DriverAlertController extends Controller
{
    // related api make_warning_read
    //[driver]
    public function makeAlertSeen(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
            'warning_id' =>'required',
           
        ]);
        $driver = User::find($request->driver_id);
        if ($driver)
        {
            $driverAlert = DriverAlert::find($request->warning_id);
            $driverAlert->seen = 1;
            $driverAlert->answer = $request->alert_answer;
            $driverAlert->update();
            return response()->json([
                "success" => true,
                "message" => "user",
                'data'=> [
                    'arabic_error' => '',
                    'english_error' => '',
                    'arabic_result' => 'تمت تعديل التحذير إلى مقروء',
                    'english_result' => 'Alert edited to seen successfully',
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => ' No data!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }

//    public function answerAlert(Request $request)
//    {
//        $request->validate([
//            'warning_id' =>'required',
//            'answer' =>'required',
//
//        ]);
//        $driverA = DriverAlert::find($request->warning_id);
//        if ($driverA)
//        {
//            $driverAlert = DriverAlert::find($request->warning_id);
//            $driverAlert->seen = 1;
//            $driverAlert->answer = $request->alert_answer;
//            $driverAlert->update();
//            return response()->json([
//                "success" => true,
//                "message" => "user",
//                'data'=> [
//                    'arabic_error' => '',
//                    'english_error' => '',
//                    'arabic_result' => 'تمت تعديل التحذير إلى مقروء',
//                    'english_result' => 'Alert edited to seen successfully',
//                ]
//            ]);
//        }
//        else {
//            return response()->json([
//                "success" => false,
//                'data' => [
//                    'arabic_error' => 'لايوجد بيانات',
//                    'english_error' => ' No data!!',
//                    'arabic_result' => '',
//                    'english_result' => '',
//                ]
//            ]);
//        }
//    }
    public function alertList(Request $request)
    {
        $request->validate([
            'driver_id' =>'required',
        ]);
        $driverAlerts = DriverAlert::where('driver_id',$request->driver_id)->get();
        if ($driverAlerts)
        {
            return response()->json([
                "success" => true,
                "message" => "user",
                'data'=> [
                    'arabic_error' => '',
                    'english_error' => '',
                    'alerts'=>$driverAlerts
                ]
            ]);
        }
        else {
            return response()->json([
                "success" => false,
                'data' => [
                    'arabic_error' => 'لايوجد بيانات',
                    'english_error' => ' No data!!',
                    'arabic_result' => '',
                    'english_result' => '',
                ]
            ]);
        }
    }
}
