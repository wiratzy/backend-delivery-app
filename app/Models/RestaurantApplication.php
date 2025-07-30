<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantApplication extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'location', 'image', 'status', 'type', 'food_type',
    ];

    protected $casts = [
        'status' => 'string',
    ];

}
