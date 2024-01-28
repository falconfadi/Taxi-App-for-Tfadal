<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{

    protected $table = 'verification_code';
    protected $verificationCodeText = 'Verification%20Code:%20';
    use HasFactory;

    public function saveCode($user_id, $verficationCode){
        $lastVerificationCode = $this->where('user_id',$user_id)->first();
        if($lastVerificationCode)
        {
            $lastVerificationCode->verification_code = $verficationCode;
            $lastVerificationCode->save();

        }else{
            $this->user_id = $user_id;
            $this->verification_code = $verficationCode;
            $this->save();
        }

    }

    public function sendVerificationCode($userId,$phone){
        $varification_code = rand(100000, 999999);
        $sendSms = new \App\Http\Controllers\Admin\SendSMSController();
        $msg = $this->verificationCodeText.$varification_code;
        $y = $sendSms->send($msg, $phone);
        //save verification code
        //$c = new VerificationCode();
        $this->saveCode($userId,$varification_code );
        return $varification_code;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

