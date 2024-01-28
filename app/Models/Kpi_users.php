<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi_users extends Model
{
    use HasFactory;


    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $fillable = [
        'sum_money_paid',
        'avg_reach_time',
        'sum_trip_cancelled	',
        'sum_trip_acheived',
        'user_id'
    ];

    public function best_users($num){
        $users = Kpi_users::with('users')
            ->skip(0)->take($num)
            ->orderBy('sum_trip_acheived','DESC')->get();
        return $users;
    }


}
