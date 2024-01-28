<?php

namespace App\Http\Controllers;

use App\Models\Border;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PointLocationController extends Controller
{
    var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?

    function pointLocation() {}

    function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;

        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array();
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex);
        }

        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            //return "vertex";
            return true;
        }
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0;
        $vertices_count = count($vertices);

        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1];
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                //return "boundary";
                return true;
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ((float)$xinters == (float)$point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    //return "boundary";
                    return true;
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++;
                }
            }
        }
        // If the number of edges we passed through is odd, then it's in the polygon.
        if ($intersections % 2 != 0) {
            //return "inside";
            return true;
        } else {
            //return "outside";
            return false;
        }
    }

    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }

    function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => (float)$coordinates[0], "y" => (float)$coordinates[1]);
    }

    public function test()
    {
//        $pois = Border::where('name','Damascus')->orderBy('order_')->get();
//        $polygon = array();
//        foreach ($pois as $poi){
//            $point = $poi->latitude." ".$poi->longitude;
//            array_push($polygon, $point);
//        }
//        $lastPoint = $pois[0]->latitude." ".$pois[0]->longitude;
//        array_push($polygon,$lastPoint);
        //print_r($polygon);echo "<br>";
//        $points = array("33.52460507638786 36.24744176918748",
//            "33.52632232275423 36.28177404515462",
//            "33.520876725188764 36.239158020863826",
//            "33.51029336401875 36.358678343320996",
//            "33.50628566028286 36.29842519899867",
//            "33.520876725188764 36.239158020863826",
//            "33.50604640563127 36.4206493874648",
//            "33.45424604116971 36.38567762911644",
//            "33.4494667777231 36.21775289290923",
//            "33.44493881151111 36.306689692157214",
//            "33.56850099030101 36.26618335025262",
//            "33.51726603407316 36.18034122866346",
//            "33.5639547374153 36.460055599434924",
//            "34.14048097179159 36.81099891298223",
//            "33.48831439830743 36.409482082659316",
//            "33.407808541120836 36.29729000243871",
//            "33.61921047549804 36.12002651569014");
        $points = array("33.516915681774535 36.315908716096864");
//        //$pointLocation = new pointLocation();
//        $points = array("50 70","70 40","-20 30","100 10","-10 -10","40 -20","110 -20");
        $polygon = array("33.563344627513 36.23723457489374",
            "33.56297494465334 36.23970633637695",
            "33.55980682191191 36.24350043064987",
            "33.555648163624795 36.2511699094104",
            "33.550613730448895 36.257000814495456",
            "33.546343917518264 36.259681159190926",
            "33.54225410526733 36.26419504511698","33.539870348319766 36.27378978516983",
            "33.539562761989274 36.28126261155715","33.545538579335506 36.29790517939157",
            "33.548112866970115 36.303276954041124","33.562773651466706 36.317915039961136");
//        // The last point's coordinates must be the same as the first one's, to "close the loop"
        foreach($points as $key => $point) {
            echo "point " . ($key+1) . " ($point): " . $this->pointInPolygon($point, $polygon) . "<br>";
        }
    }

    public function test1()
    {



    }


}
