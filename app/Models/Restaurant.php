<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'rate',
        'rating',
        'type',
        'food_type',
        'location',
        'is_most_popular',
        'restaurant_category_id',
        'owner_id'
    ];

    public function category()
    {
        return $this->belongsTo(RestaurantCategory::class, 'restaurant_category_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


}
