<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Car_model;
use App\Models\Color;
use Illuminate\Http\Request;
use stdClass;

class CarModelController extends Controller
{
    public function brands()
    {

        $ps = Brand::all();
        $colors = Color::all();
        return response()->json(
            [
                'message' => 'brands',
                'data' => [
                    'brands' => $ps,
                    'colors' => $colors
                ]
            ]
        );
    }
    public function car_models()
    {

        $ps = Car_model::all();
        return response()->json(
            [
                'message' => 'models',
                'data' => [
                    'models' => $ps,
                ]
            ]
        );
    }

    public function car_models_by_brand(Request $request)
    {
        $request->validate([
            'brand_id' => 'required'
        ]);
        $models = Car_model::where('brand_id',$request->brand_id)->get();
        if($models)
        {
            $car_model  = new stdClass();
            $car_model->id = 0;
            $car_model->model = "Other";
            $car_model->brand_id = $request->brand_id;

            //add new item to models
            $models->push($car_model);
            return response()->json(
                [
                    'message' => 'models',
                    'data' => [
                        'models' => $models,
                    ]
                ]
            );
        }
        else
        {
            return response()->json(
                [
                    'message' => 'models',
                    'data' => [
                        'arabic_error' => 'لا يوجد بيانات',
                        'english_error' => 'No data',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]
            );
        }
    }

}
