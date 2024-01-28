<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Request_edit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RequestEditController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('auth:admin');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = __('page.Edit_Requests');
        $requests = Request_edit::with('driver')->get();
        //$brands = Brand::all();
        //var_dump();
        return view('admin.request_edit.index',compact('requests','title'));
    }



}
