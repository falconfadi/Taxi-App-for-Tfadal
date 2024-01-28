<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiTripOffers extends Model
{
    use HasFactory;
    protected $table = 'multi_trips_offers';
    protected $fillable = [
        'user_id', 'offer_id', 'num_of_trips'
    ];
}
