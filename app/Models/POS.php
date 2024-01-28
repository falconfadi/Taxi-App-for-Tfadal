<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class POS extends Model
{
    use HasFactory;

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'pos_id');
    }
    public function saveRecord($driver_id, $amount){
        $this->pos_id  = Auth::guard('admin')->user()->id;
        $this->driver_id  = $driver_id;
        $this->amount  = $amount;
        $this->save();
    }

    public function getPOS(){
        $poses = Admin::wherehas('roles',function($q) {
                 $q->where('name','POS');
             })->get();
        return $poses;
    }
}
