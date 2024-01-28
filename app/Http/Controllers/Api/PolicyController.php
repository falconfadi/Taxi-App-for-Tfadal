<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function privacyPolicy()
    {
        $policies = Policy::where('id',1)->first();

        return response()->json(
            [
                'message'=>'policies',
                'data'=> [
                    'arabic_privacy'=> $policies->arabic_privacy,
                    'english_privacy' =>$policies->english_privacy,

                ]
            ]
        );
    }
    public function whoWeAre()
    {
        $policies = Policy::where('id',1)->first();

        return response()->json(
            [
                'message'=>'policies',
                'data'=> [
                    'arabic_who_we_are'=> $policies->arabic_who_we_are,
                    'english_who_we_are' =>$policies->english_who_we_are
                ]
            ]
        );
    }

}
