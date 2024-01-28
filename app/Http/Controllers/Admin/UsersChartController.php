<?php

namespace App\Http\Controllers\Admin;

use App\Charts\UsersChart;
use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;

class UsersChartController extends Controller
{
    public function index(UsersChart  $chart){
        return view('admin.charts.users',['chart' => $chart->build()]);
    }

}
