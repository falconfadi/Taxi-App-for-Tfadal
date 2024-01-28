<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offers;
use App\Models\Point;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PointsController extends Controller
{

    public function getUserPoints(Request $request)
    {
        $request->validate([
            'user_id' => 'required'  ]);
        $points = Point::where('user_id',$request->user_id)->first();
        $s = new Setting();
        $setting = $s->getSetting();
        if($points)
        {
            return response()->json(
                [
                    "success" => true,
                    'data'=> [
                        'points'=>$points->points,
                        'is_enabled_points' =>$setting->is_enabled_points,
                        'arabic_message' =>'يمكنك الاستفادة من النقاط والحصول على حسم للفاتورة',
                        'english_message' =>'you may use points to take discount on your bills',
                        'arabic_message_1' =>'استفد من عروض النقاط واحسل على رحلات مجانية',
                        'english_message_2' =>'get points and use them to go and drive more',
                    ]
                ]
            );
        }
        else{
            return response()->json([
                "success" => false,
                'data' => [
                    'points'=>0,
                    'is_enabled_points' =>$setting->is_enabled_points,
                    'arabic_error' => 'لايوجد نقاط',
                    'english_error' => 'No Points',
                    'arabic_result' => '',
                    'english_result' => '',
                    'arabic_message' =>'يمكنك الاستفادة من النقاط والحصول على حسم للفاتورة',
                    'english_message' =>'you may use points to take discount on your bills',
                    'arabic_message_1' =>'استفد من عروض النقاط واحسل على رحلات مجانية',
                    'english_message_2' =>'get points and use them to go and drive more',
                ]
            ]);
        }
    }
    public function getUserPointsWeb($user_id)
    {

        $points = Point::where('user_id',$user_id)->first();
        $s = new Setting();
        $setting = $s->getSetting();
        if($points)
        {
            return $points->points;
        }
        else{
            return 0;
        }
    }

    public function checkPointsAvailabilty($user_id){
        $set = new Setting();
        $setting  = $set->getSetting();

        //$is_have_point_discount = false;
        if($setting->is_enabled_points){
            $points = $this->getUserPointsWeb($user_id);
            if($points > $setting->min_num_of_points )
               return true;
            else
                return false;
        }
        else{
            return false;
        }
    }
    //apply Discount By Offer and return what remain from price
    public function applyDiscountByOffer($trip ,$oldPrice ,$distance){
        $now = Carbon::now();
        $offer = Offers::where('is_customized',1)->whereDate('start_time', '<=', $now)->whereDate('end_time', '>=', $now)->first();
        $points = $this->getUserPointsWeb($trip->user_id);
        $restOfPoints = $points;
        $userPoint = Point::where('user_id',$trip->user_id)->first();
        if($offer && $offer->kilometers==0){ //offer by price
            if($points >= $offer->points){
                //$howPointsCanCover = (int)$points / (int)$offer->points;
                $howNeedPointsToCoverPrice = (int)$offer->points*$oldPrice/(int)$offer->price;
                if($points < $howNeedPointsToCoverPrice){
                    $ratio = $points / $howNeedPointsToCoverPrice;
                    $restOfPoints = 0;
                    //update points to user
                    $userPoint->points = $restOfPoints;
                    $userPoint->update();
                    return (1- $ratio)*$oldPrice;
                }

                else{
                    $restOfPoints = $points - $howNeedPointsToCoverPrice;
                    //update points to user
                    $userPoint->points = $restOfPoints;
                    $userPoint->update();
                    return 0;
                }
            }
            else{
                return -1;
            }
        }
        elseif($offer && $offer->price==0){//offer by kilometers
            if($points >= $offer->points){
                $howNeedPointsToCoverDistance = (int)$offer->points*$distance/(int)$offer->kilometers;
                //echo "howNeedPointsToCoverDistance: "; echo $howNeedPointsToCoverDistance;echo "<br>";
                if($points < $howNeedPointsToCoverDistance){
                    $ratio = $points / $howNeedPointsToCoverDistance;
                    echo "ratio: "; echo $ratio;echo "<br>";
                    $restOfPoints = 0;
                    //update points to user
                    $userPoint->points = $restOfPoints;
                    $userPoint->update();
                    return (1- $ratio)*$oldPrice;
                }
                else{
                    $restOfPoints = $points - $howNeedPointsToCoverDistance;
                    //update points to user
                    $userPoint->points = $restOfPoints;
                    $userPoint->update();
                    return 0;
                }
            }
            else{
                return -1;
            }
        }
        else{
            return -1;
        }
    }

    public function test(){

        //20,50
       $this-> makeDiscountByOffer(20,50);
    }

}
