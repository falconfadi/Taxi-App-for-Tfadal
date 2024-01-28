<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offers;
use App\Models\OffersCodeTaken;
use App\Models\Point;
use App\Models\Promotion;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\NotificationsController;
use Illuminate\Support\Facades\DB;

class OffersController extends Controller
{
    //get promotions by user
    public function promotions(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $ps = Promotion::where('user_id',$request->user_id)->get();
        if($ps)
        {
            return response()->json(
                [
                    'message' => 'Promotions',
                    'data' => [
                        'promotions' => $ps,
                    ]
                ]
            );
        }else{
            return response()->json(
                [
                    'message' => 'Promotions',
                    'data' => [
                        'arabic_error' => 'لايوجد ترقيات خاصة بك',
                        'english_error' => 'No Promotions ',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]
            );
        }
    }


    public function add_promotion(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'code' => 'required'
        ]);
        $offers = Offers::where('code', $request->code)->first();

        if ($offers) {
            //$offerToUser = Off
            $prom = new Promotion();
            $prom->code = $request->code;
            $prom->user_id = $request->user_id;
            $prom->arabic_title = "";
            $prom->english_title = "";
            $prom->arabic_description = "";
            $prom->english_description = "";
            if ($prom->save()) {
                return response()->json(
                    [
                        'message' => 'Add Promotion',
                        'data' => [
                            'arabic_error' => '',
                            'english_error' => '',
                            'arabic_result' => ' تم ',
                            'english_result' => 'Promotion added',
                        ]
                    ]
                );
            } else {
                return response()->json(
                    [
                        'message' => 'Add Promotion',
                        'data' => [
                            'arabic_error' => 'لم يتم إضافة ترقية خاصة بك',
                            'english_error' => 'Promotion Not added',
                            'arabic_result' => '',
                            'english_result' => '',
                        ]
                    ]
                );
            }
        } else {
            return response()->json(
                [
                    'message' => 'Add Promotions',
                    'data' => [
                        'arabic_error' => 'لا يوجد عرض لهذا الكود',
                        'english_error' => 'Promotion Not found',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]
            );
        }
    }

    //send offer to user as notification
    public function send_offer(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);
        $points = Point::where('user_id', $request->user_id)->first();

        if ($points) {
            if ($points->points >= 30) {
                $user = User::findOrFail($request->user_id);
                $title = 'New Offer';
                $body = 'you have discount 50% on the next trip';
                FCMService::send(
                    $user->fcm_token,
                    [
                        'title' => $title,
                        'body' => $body,
                    ],
                    [
                        'message' => ''
                    ],
                );
                // save notification

                //save offer

            } else {
                return response()->json(
                    [
                        'message' => 'Points',
                        'data' => [
                            'arabic_error' => 'رصيد النقاط غير كافي',
                            'english_error' => 'no enough points',
                            'arabic_result' => '',
                            'english_result' => '',
                        ]
                    ]
                );
            }
        } else {
            return response()->json(
                [   'message' => 'Points',
                    'data' => [
                        'arabic_error' => 'لا يوجد نقاط',
                        'english_error' => 'no points',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]
            );
        }
    }

    public function get_by_code(Request $request){
        $request->validate([
            'user_id' => 'required',
            'code' => 'required'
        ]);
        $o = new Offers();
        $offer = $o->getOfferByCode($request->user_id , $request->code);
        if($offer)
        {
            return response()->json(
                [
                    'message' => 'offer by code',
                    'data' => [
                        'offer' => $offer,
                    ]
                ]
            );
        }
        else{
            return response()->json(
                [
                    'message' => 'offer by code',
                    'data' => [
                        'arabic_error' => 'لا يوجد عروض أو أن العرض منته',
                        'english_error' => 'no offers',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]
            );
        }
    }
    public function my_offers(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        //$offers = Promotion::all();
        $offers = DB::table('offers_users')
            ->select()
            ->join('offers', 'offers_users.offer_id', '=', 'offers.id')
            ->where('offers_users.user_id', $request->user_id)
            ->get();
        if($offers)
        {
            return response()->json(
                [
                    'message' => 'offers',
                    'data' => [
                        'offers' => $offers,
                    ]
                ]
            );
        }
        else{
            return response()->json(
                [
                    'message' => 'Offers',
                    'data' => [
                        'arabic_error' => 'لا يوجد عروض',
                        'english_error' => 'no offers',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]
            );
        }
    }

    public function insert_code(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'code' => 'required'
        ]);

        $o = new Offers();
        $offer = $o->getOfferByCode($request->user_id, $request->code);
        if($offer)
        {
            $insertedBefore = OffersCodeTaken::where('user_id',$request->user_id)->where('offer_id',$offer->id)->first();
            if($insertedBefore){
                return response()->json(
                    [
                        'message' => 'offer',
                        'data' => [
                            'offers' => $offer,
                            'arabic_error' => 'لقد استخدمت الكود سابقاً',
                            'english_error' => 'Code id used before',
                            'arabic_result' => '',
                            'english_result' => '',
                        ]
                    ]
                );
            }else{
                $takeOffer = new OffersCodeTaken();
                $takeOffer->user_id = $request->user_id;
                $takeOffer->offer_id = $offer->id;
                $takeOffer->is_used = 0;
                $takeOffer->save();
                return response()->json(
                    [
                        'message' => 'offer',
                        'data' => [
                            'offers' => $offer,
                            'arabic_error' => '',
                            'english_error' => '',
                            'arabic_result' => 'تمت إضافة كود الحسم بنجاح',
                            'english_result' => 'Code added successfully',
                        ]
                    ]
                );
            }
        }
        else{
            return response()->json(
                [
                    'message' => 'Offers',
                    'data' => [
                        'arabic_error' => 'لا يوجد عروض',
                        'english_error' => 'no offers',
                        'arabic_result' => '',
                        'english_result' => '',
                    ]
                ]
            );
        }
    }
}
