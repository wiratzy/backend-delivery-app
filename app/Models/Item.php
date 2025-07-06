<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'rate',
        'rating',
        'type',
        'restaurant_id',
        'item_category_id',
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

    // ✅ FIX INI: relasi itemCategory() ditulis dengan benar dan DI DALAM CLASS
    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function getImageAttribute($value)
    {
        return url('storage/items/' . $value);
    }
}
