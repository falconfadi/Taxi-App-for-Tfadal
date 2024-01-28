<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;

class Offers extends Model
{
    use HasFactory;
    use LogsActivity;

    public function users()
    {
        return $this->belongsToMany(User::class,'offers_users','offer_id','user_id' );
    }
    public function checkOffers($user_id){
//        $offers = Offers::wherehas('users',function($q) use($user_id){
//            $q->where('user_id',$user_id);
//        })
//           ->whereDate('end_time','>=',Carbon::today())
//           ->whereDate('start_time','<=',Carbon::today())
        $offers = $this->wherehas('users',function($q) use($user_id){
            $q->where('user_id',$user_id)
                ->whereDate('end_time','>=',Carbon::today())
                ->whereDate('start_time','<=',Carbon::today());
        })
        ->orWhere(function($q1){
            $q1->where('is_all',1)
                ->whereDate('end_time','>=',Carbon::today())
                ->whereDate('start_time','<=',Carbon::today());
        })
       ->get();

        $checkOffers =  ($offers)?$offers:false;
        $checkOffersArr = array();
        if($checkOffers){
            foreach ($checkOffers as $offer) {
                if ($offer->type==0) {
                    //multi trip win
                    $mTripOffer = MultiTripOffers::where('user_id', $user_id)->where('offer_id', $offer->id)->first();
                    if ($mTripOffer) {
                        if ($mTripOffer->num_of_trips < $offer->num_of_trips) {
                            $offer->remaining_trips_count = $offer->num_of_trips - $mTripOffer->num_of_trips;
                            $offer->remaining_balance = $offer->remaining_trips_count*$offer->amount/$offer->num_of_trips;
                            array_push($checkOffersArr, $offer);
                        }
                    }else{
                        $offer->remaining_trips_count = $offer->num_of_trips ;
                        $offer->remaining_balance = $offer->amount;
                        array_push($checkOffersArr, $offer);
                    }
                }elseif($offer->type == 1){
                    //offer with Code
                    $codeInserted = OffersCodeTaken::where('offer_id',$offer->id)->where('user_id',$user_id)->where('is_used',1)->first();
                    if($codeInserted){

                    }else{
                        array_push($checkOffersArr, $offer);
                    }
                }
                elseif($offer->type==2) {
                    $offerUsed = FreeTripOffer::where('offer_id',$offer->id)->where('user_id',$user_id)->first();
                    if($offerUsed){
                    }else{
                        array_push($checkOffersArr, $offer);
                    }
                }
                elseif ($offer->type==3) {
                    //multi trip win
                    $fourthOffer = FourthWinOffer::where('user_id', $user_id)->where('offer_id', $offer->id)->first();
                    if ($fourthOffer) {
                        if ($fourthOffer->num_of_trips < $offer->num_of_trips) {
                         array_push($checkOffersArr, $offer);
                        }
                    }else{
                       array_push($checkOffersArr, $offer);
                    }
                }

            }
        }
        return $checkOffersArr;
    }

    public function checkOffersOfNewUser($user_id){
        $offers = $this->wherehas('users',function($q) use($user_id){
            $q->where('user_id',$user_id);
        })
            ->where('end_time','>=',Carbon::now()->timestamp)
            ->where('is_new_client',1)
            ->first();
        return $offers;
    }



    public function getOfferByCode($user_id,$code){
        $offer = $this->wherehas('users',function($q) use($user_id){
            $q->where('user_id',$user_id);
        })
        ->whereDate('end_time','>=',Carbon::today())
        ->whereDate('start_time','<=',Carbon::today())
        ->where('code',$code)

        ->orWhere(function($q) use($code){
            $q->Where('is_all',1)
                ->whereDate('end_time','>=',Carbon::today())
                ->whereDate('start_time','<=',Carbon::today())
                ->where('code',$code);
        })
        ->first();
        return $offer;
    }

    public function checkDiscountAvailabilty($trip, $oldPrice){
        $offer = $this->wherehas('users',function($q) use($trip){
            $q->where('user_id',$trip->user_id)
            ->whereDate('end_time','>=',Carbon::today())
                ->whereDate('start_time','<=',Carbon::today());
        })
        ->orWhere(function($q1){
            $q1->where('is_all',1)
            ->whereDate('end_time','>=',Carbon::today())
                ->whereDate('start_time','<=',Carbon::today());
        })
        ->first();
        $discount = 0; $freeTrip= false;
        if($offer){
            if($offer->type == 0){
                //offer with multi trips
                //echo "in";
                $discount = $offer->amount/$offer->num_of_trips;
                $mTripOfferNew = new MultiTripOffers();
                $mTripOffer = MultiTripOffers::where('user_id',$trip->user_id)->where('offer_id',$offer->id)->first();
                if($mTripOffer)
                {
                    if($mTripOffer->num_of_trips==$offer->num_of_trips){
                        $discount = 0;
                    }else{
                        $mTripOffer->num_of_trips +=1;
                        $mTripOffer->save();
                    }
                }
                else{
                    $mTripOfferNew->user_id = $trip->user_id;
                    $mTripOfferNew->offer_id = $offer->id;
                    $mTripOfferNew->num_of_trips = 1;
                    $mTripOfferNew->save();
                }
            }
            elseif($offer->type == 1){
                //offer with Code
                $codeInserted = OffersCodeTaken::where('offer_id',$offer->id)->where('user_id',$trip->user_id)->where('is_used',0)->first();
                if($codeInserted){
                    $codeInserted->is_used = 1;
                    $codeInserted->save();
                    //amount not ratio
                    $discount = $offer->discount;
                    //return $discount;
                }
            }
            elseif ($offer->type==2){
                //free trip offer
                $offerUsed = FreeTripOffer::where('offer_id',$offer->id)->where('user_id',$trip->user_id)->first();
                if($offerUsed){
                    $discount = 0;
                }else{
                    $freeTrip = true;
                    $free = new FreeTripOffer();
                    $free->offer_id = $offer->id;
                    $free->user_id = $trip->user_id;
                    $free->save();
                    $discount = $oldPrice;
                }
            }
            if($offer->type == 3){
                //offer with multi trips
                //$discount = $offer->amount/$offer->num_of_trips;
                $fourthWinOfferModel = new FourthWinOffer();
                $fourthWinOffer = FourthWinOffer::where('user_id',$trip->user_id)->where('offer_id',$offer->id)->first();
                if($fourthWinOffer)
                {
                    $fourthWinOffer->num_of_trips +=1;
                    $fourthWinOffer->save();
                    if($fourthWinOffer->num_of_trips == $offer->num_of_trips){
                        $discount = $oldPrice;
                    }
                }
                else{
                    $fourthWinOfferModel->user_id = $trip->user_id;
                    $fourthWinOfferModel->offer_id = $offer->id;
                    $fourthWinOfferModel->num_of_trips = 1;
                    $fourthWinOfferModel->save();

                }
            }
        }
        if($trip->is_scheduled==1 && !$freeTrip){
            $tSetting = TripSetting::find(1);
            if($tSetting){
                $discount +=$tSetting->schedule_trip_discount;
            }
        }

        if ($discount>$oldPrice) $discount = $oldPrice;
        return $discount;
    }

    public function execludeUsersHavingOldOffers($usersIds)
    {
        //$newUsersIds = array();
        for($i=0; $i<count($usersIds);$i++){
            $x = $this->checkOffers($usersIds[$i]);
            if(count($x)>0){
                //var_dump($x);exit();
                unset($usersIds[$i]);
            }
        }
        return $usersIds;
    }

    public function allOffersByUserId($user_id){
        $offers = $this->wherehas('users',function($q) use($user_id){
            $q->where('user_id',$user_id);
        })
        ->orWhere(function($q1){
            $q1->where('is_all',1);
        })
        ->orderBy('id','DESC')->get();
        return $offers;
    }

    public function getActivitylogOptions():LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['details'])
            /*->logOnlyDirty()*/;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = Auth::guard('admin')->user()->getAuthIdentifier();
    }
}
