<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SyriatelController extends Controller
{
    public function getSyriatelMerchantToken()
    {
        $ch = curl_init();

        $url = "https://Merchants.syriatel.sy:1443/ePayment_external_Json/rs/ePaymentExternalModule/getToken/";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);

        $params = ['username'=>'TFadal','password'=>'Tf@d@L25675'];
        $json = json_encode($params);
        ///$params = 'username=TFadal&password=Tf@d@L25675';
        $username = 'TFadal';$password = 'Tf@d@L25675';
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
       // curl_setopt($ch, CURLOPT_POSTFIELDS, $json); // define what you want to post
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
               /* 'Content-Length: ' . strlen($json)*/
            ]
        );
        $output = curl_exec($ch);

        curl_close($ch);

        return response()->json([
            'data' => [
                'output' => $output,

            ]
        ]);
    }

}
