<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SendSMSController extends Controller
{
    public function sendMsg()
    {
        $url = 'http://test.friends-sy.tel/api/sendSMSApi';
        
    }
}
