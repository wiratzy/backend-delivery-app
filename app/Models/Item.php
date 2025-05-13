<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'rate',
        'rating',
        'type',
        'location',
        'restaurant_id',
        'price'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function Itemcategory()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }
}
