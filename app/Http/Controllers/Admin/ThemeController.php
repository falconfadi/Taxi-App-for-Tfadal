<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Driver;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        $title =  __('menus.Cars');
        $cars = Car::all();
        $driversIds = array();

        return view('admin.theme.index');
    }
    public function index1()
    {
        $title =  __('menus.Cars');
        $cars = Car::all();
        $driversIds = array();

        return view('admin.theme.test');
    }
}
