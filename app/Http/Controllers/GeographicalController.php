<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \KMLaravel\GeographicalCalculator\Facade\GeoFacade;

class GeographicalController extends Controller
{
    public function distance($latitudeFrom=0, $longitudeFrom=0, $latitudeTo=0, $longitudeTo=0){
        //33.51998544322825 , 36.300652625874385  ,33.517411996837254  ,36.31975157387782
        $distance = GeoFacade::setPoint([$latitudeFrom, $longitudeFrom])
            ->setOptions(['units' => ['km']])
            // you can set unlimited lat/long points.
            ->setPoint([$latitudeTo, $longitudeTo])
            // get the calculated distance between each point
            ->getDistance();
        return $distance;
    }

    public function test()
    {
        $d = $this->distance();
        var_dump($d);
    }
}
