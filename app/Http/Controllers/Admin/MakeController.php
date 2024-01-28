<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Make;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use App\Models\Profile;
//use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class MakeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $title = 'Car Make';
        $makes = Make::all();
        //var_dump();
        return view('admin.make.index',compact('makes','title'));
    }
    public function create()
    {
        $title = 'Car Make';
        return view('admin.make.create',compact('title'));
    }

    public function store(Request $request)
    {
        $make = new Make();

        $make->make = $request->input('name');

        //$setting->update();
        if ($make->save()) {
            //$request->session()->flash('alert-success', __('Setting has been Edited'));
            Session::flash('message','New "Make" has been Added');
            return redirect('admin/make');
        } else {

            Session::flash('message','"Make" Not Added !!');
            return redirect('admin/make');
        }

    }
}
