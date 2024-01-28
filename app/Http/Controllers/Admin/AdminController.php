<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Offers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{
    public function __construct()
    {
         //$this->middleware('auth:admin');

    }
    public function index()
    {
        return view('fad');
    }

    public function test()
    {

    $user_id = 1911;

    $o = new Offers();
    $c = $o->checkDiscountAvailabilty($user_id,50000);
    echo $c;
    }

}
