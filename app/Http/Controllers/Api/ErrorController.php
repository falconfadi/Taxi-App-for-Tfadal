<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HandleError;
//use App\Models\HandleErrpor;
use Illuminate\Http\Request;

class ErrorController extends Controller
{

    public function handleErrors(Request $request)
    {
//        $request->validate([

        $errors = new HandleError();
        $errors->reason = $request->reason;
        $errors->api_name = $request->api_name;
        $errors->user_id = ($request->user_id)?$request->user_id:"";
        $errors->save();

    }


}
