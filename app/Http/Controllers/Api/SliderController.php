<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FreeTripOffer;
use App\Models\MultiTripOffers;
use App\Models\Offers;
use App\Models\OffersCodeTaken;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function sliders()
    {
        $sliders = Slider::all();

        return response()->json(
            [
                'message'=>'sliders',
                'data'=> [
                    'sliders'=> $sliders
                ]
            ]
        );
    }

    public function slidersWithOffers(Request $request)
    {
        $sliders = Slider::all();
        //check offers
        $o = new Offers();
        $offers = $o->checkOffers($request->user_id);

//        $checkOffers =  ($off)?$off:false;
//        $checkOffersArr = array();
//        if($checkOffers){
//            foreach ($checkOffers as $offer) {
//                if ($offer->type==0) {
//                    $mTripOffer = MultiTripOffers::where('user_id', $request->user_id)->where('offer_id', $offer->id)->first();
//                    if ($mTripOffer) {
//                        if ($mTripOffer->num_of_trips < $offer->num_of_trips) {
//                            $offer->remaining_trips_count = $offer->num_of_trips - $mTripOffer->num_of_trips;
//                            $offer->remaining_balance = $offer->remaining_trips_count*$offer->amount/$offer->num_of_trips;
//                            array_push($checkOffersArr, $offer);
//                        }
//                    }else{
//                        $offer->remaining_trips_count = $offer->num_of_trips ;
//                        $offer->remaining_balance = $offer->amount;
//                        array_push($checkOffersArr, $offer);
//                    }
//                } elseif($offer->type==2) {
//                    $offerUsed = FreeTripOffer::where('offer_id',$offer->id)->where('user_id',$request->user_id)->first();
//                    if($offerUsed){
//
//                    }else{
//
//                        array_push($checkOffersArr, $offer);
//                    }
//                }
//                elseif($offer->type == 1){
//                    //offer with Code
//                    $codeInserted = OffersCodeTaken::where('offer_id',$offer->id)->where('user_id',$request->user_id)->where('is_used',1)->first();
//                    if($codeInserted){
//
//                    }else{
//                        array_push($checkOffersArr, $offer);
//                    }
//                }
//            }
//        }

        return response()->json(
            [
                'message'=>'sliders',
                'data'=> [
                    'sliders'=> $sliders,
                    'checkOffers' => $offers
                ]
            ]
        );
    }
}
