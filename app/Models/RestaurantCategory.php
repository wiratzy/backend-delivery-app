<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}
