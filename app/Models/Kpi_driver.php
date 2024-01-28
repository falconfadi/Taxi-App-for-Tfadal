<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi_driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'sum_money_acheived',
        'avg_reach_time',
        'sum_trip_cancelled	',
        'sum_trip_acheived',
        'driver_id'
    ];

    public function drivers()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function best_drivers($num){
        $drivers = Kpi_driver::with('drivers')
            ->skip(0)->take($num)
            ->orderBy('sum_trip_acheived','DESC')->get();
        return $drivers;
    }


}
