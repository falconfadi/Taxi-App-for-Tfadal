<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoordinateController extends Controller
{
    public function drawPath(){
        $key = env('GOOGLE_MAPS_API_KEY');

        return view('admin.trips.map',compact('key'));
    }
}
