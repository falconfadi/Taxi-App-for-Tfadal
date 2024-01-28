<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Border extends Model
{
    use HasFactory;

    protected $table = 'borders';
    protected $fillable = [
        'name',
        'order',
        'latitude',
        'longitude',
        'address',
        'area'
    ];

}
