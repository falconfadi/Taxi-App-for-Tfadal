<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarwayRegion extends Model
{
    use HasFactory;
    protected $table = 'farway_regions';

    protected $fillable = [
        'from ',
        'to',
        'price',

    ];

    public function getRange($extraDistance){
        $range = FarwayRegion::where('from','<=',$extraDistance)->where('to','>=',$extraDistance)->first();
        if ($range)
        return $range->price;
        else
        return 0;
    }

}
