<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cancel_reason;
use App\Models\Faq;
use App\Models\Feedbak_reason;
use App\Models\Rate;
use App\Models\Term;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class NoAuthController extends Controller
{

    public function faqs()
    {
        $faqs = Faq::all();

        return response()->json(
            [
                'message'=>'faqs',
                'data'=> [
                    'faqs'=> $faqs,
                ]
            ]
        );
    }
    public function terms()
    {
        $terms = Term::where('id',1)->first();

        return response()->json(
            [
                'message'=>'Terms',
                'data'=> [
                    'arabic_terms'=> $terms->arabic_terms,
                    'english_terms' =>$terms->arabic_terms
                ]
            ]
        );
    }
    public function cancel_trip_reasons()
    {
        $reasons = Cancel_reason::where('for_users',1)->get();
        $reasons_drivers = Cancel_reason::where('for_users',0)->get();
        $x = Feedbak_reason::all();
        return response()->json(
            [
                'message'=>'Cancel reasons',
                'data'=> [
                    'reasons_users'=> $reasons,
                    'reasons_drivers'=> $reasons_drivers,
                    'feed_back_reasons'=>$x
                ]
            ]
        );
    }



}
