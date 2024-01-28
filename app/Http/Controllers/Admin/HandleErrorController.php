<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HandleError;
use Illuminate\Http\Request;

class HandleErrorController extends Controller
{
    public function __construct()
    {
        parent::__construct();

//        $this->middleware('auth:admin');
//        App::setLocale('ar');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title =  "Errors";
        $errors = HandleError::all();

        return view('admin.errors.index',compact('errors','title'));

        //
    }

    public function destroy($id)
    {
        $res = HandleError::find($id)->delete();

        return back()->with('success','Faq deleted successfully');
    }
}
