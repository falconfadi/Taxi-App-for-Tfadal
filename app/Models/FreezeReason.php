<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreezeReason extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'reason',
        'is_freeze'
    ];



    public  function isFreezed($user_id){
        $freeze = $this->where('user_id', $user_id)->orderBy('id', 'desc')->limit(1)->first();
        if($freeze){
            if($freeze->is_freeze==1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
