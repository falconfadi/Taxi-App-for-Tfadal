<?php

namespace App\Http\Controllers;

use App\Models\Policy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = __('menus.Home');
        $policy = Policy::find(1);
        //var_dump($policy);exit();
        return view('website.index',compact('title','policy'));
    }

    public function privacy()
    {
        $title = __('menus.policy');
       // $setting = $this->setting;
        $privacy = Policy::find(1);
        return view('website.privacy',compact('title','privacy'));
    }
}
