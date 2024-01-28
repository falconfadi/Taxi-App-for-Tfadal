<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class UserLocationsController extends Controller
{
    public function myPlaces(Request $request)
    {
        $request->validate([
            'id' =>'required',
        ]);

        $locations = Location::where('user_id',$request->id)->get();
        return response()->json(
            [
                'message'=>'my places',
                'data'=> [
                    'places'=>$locations
                ]
            ]
        );
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' =>'required',
            'latitude'=> 'required',
            'longitude'=> 'required',
            'place_type'=>'required',
            'id'=>'required'
        ]);

        $location = new Location();
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->title = $request->title;
        $location->place_type = $request->place_type;
        $location->place_type_title = $request->place_type_title;
        $location->user_id = $request->id;

        if($location->save())
        {
            return response()->json(
                [
                    'message'=>'Add Location',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>' تم إضافة مكان جديد',
                        'english_result'=>'success to add place',
                    ]
                ]
            );
        }else{
            return response()->json(
                [
                    'message'=>'Add Location',
                    'data'=> [
                        'arabic_error'=>'لم تتم إضافة مكان جديد',
                        'english_error'=>'Place not added',
                        'arabic_result'=>'',
                        'english_result'=>'',
                    ]
                ]
            );
        }
    }

    public function update(Request $request){
        $request->validate([
            'place_id' =>'required',
            'id'=>'required'
        ]);
        $location = Location::where('id',$request->place_id)->first();
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->title = $request->title;
        $location->place_type = $request->place_type;
        $location->place_type_title = $request->place_type_title;
        if($location->save())
        {
            return response()->json(
                [
                    'message'=>'Edit Location',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>' تم تعديل المكان ',
                        'english_result'=>'success to edit place',
                    ]
                ]
            );
        }else{
            return response()->json(
                [
                    'message'=>'Edit Location',
                    'data'=> [
                        'arabic_error'=>'لم يتم تعديل المكان',
                        'english_error'=>'Place not edited',
                        'arabic_result'=>'',
                        'english_result'=>'',
                    ]
                ]
            );
        }
    }
    public function delete(Request $request){

        $request->validate([
            'place_id' =>'required'
        ]);
        $count = Location::where('id',$request->place_id)->delete();
        // dd($data);
        if($count > 0 ){
            return response()->json([
                'message'=>'Successfully Deleted',
                'data'=> [
                    'arabic_error'=>'',
                    'english_error'=>'',
                    'arabic_result'=>' تم حذف المكان ',
                    'english_result'=>'Success to delete place',
                ]
            ]);
        }
        else{
            return response()->json([
                'message'=>'Delete Failed',
                'data'=> [
                    'arabic_error'=>'لم يتم حذف المكان',
                    'english_error'=>'Place not deleted',
                    'arabic_result'=>'',
                    'english_result'=>'',
                ]
            ]);
        }
    }
}
