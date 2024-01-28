<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function cities()
    {
        $cities = City::all();
        if($cities)
        {
            return response()->json(
                [
                    'message' => 'cities',
                    'data' => [
                        'cities' => $cities,
                    ]
                ]
            );
        }
        else
        {
            return response()->json([
                'message' => 'cities',
                    'data' => [
                        'arabic_error' => 'لا يوجد بيانات',
                        'english_error' => 'No data',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
        }
    }
    public function regions(Request $request)
    {
        $request->validate([
            'city_id' => 'required'
        ]);
        if($request->city_id==0){
            $regs = Region::all();
            $regions = array();
            foreach ($regs as $regItem){
                $reg = new Region();
                $reg->id = $regItem->id;
                $reg->region = $regItem->region;
                $reg->region_ar = $regItem->region_ar."-".$regItem->city->name_ar;
                array_push($regions,$reg);
            }
            return response()->json([
                'message' => 'regions',
                'data' => [
                    'regions' => $regions,
                ]
            ]);
        }else{
            $regions = Region::where('city_id',$request->city_id)->get();
            if($regions)
            {
                return response()->json([
                    'message' => 'regions',
                    'data' => [
                        'regions' => $regions,
                    ]
                ]);
            }
            else
            {
                return response()->json([
                    'message' => 'regions',
                    'data' => [
                        'arabic_error' => 'لا يوجد بيانات',
                        'english_error' => 'No data',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]);
            }
        }

    }
}
