<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request_edit extends Model
{
    use HasFactory;
    protected $table = 'request_edit';


    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

}
