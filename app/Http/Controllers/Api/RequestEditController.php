<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request_edit;
use Illuminate\Http\Request;

class RequestEditController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendEditCarRequest(Request $request)
    {

        return response()->json(
            [
                'message' => 'Sending Request',
                'data' => [
                    'arabic_error' =>  ' الرجاء التواصل مع الإدارة',
                    'english_error' => 'Please Contact Call Center',
                    'arabic_result' => ' الرجاء التواصل مع الإدارة',
                    'english_result' => 'Please Contact Call Center',
                ]
            ]
        );



    }

}
