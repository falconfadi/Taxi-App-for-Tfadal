<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    //add  Suggestion by user
    //[user] [driver]
    public function add_suggestion(Request $request)
    {
        $request->validate([
            'user_id' =>'required'
        ]);
        $comp = new Suggestion();
        $comp->user_id = $request->user_id;
        $comp->text = $request->text;

        if($comp->save())
        {
            return response()->json(
                [
                    'message'=>'Add Suggestion',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>' تم إضافة مقترح',
                        'english_result'=>'Suggestion added',
                    ]
                ]
            );
        }
        else{
            return response()->json(
                [
                    'message'=>'Add Suggestion',
                    'data'=> [
                        'arabic_error'=>'لم يتم إضافة مقترح',
                        'english_error'=>'Suggestion Not added',
                        'arabic_result'=>'',
                        'english_result'=>'',
                    ]
                ]
            );
        }
    }

    //by user id
    public function suggestions_list(Request $request)
    {
        $request->validate([
            'user_id' =>'required'
        ]);

        $comps = Suggestion::where('user_id',$request->user_id)->get();
        return response()->json(
            [
                'message'=>'Suggestions',
                'data'=> [
                    'Suggestions'=>$comps,
                ]
            ]
        );
    }
}
