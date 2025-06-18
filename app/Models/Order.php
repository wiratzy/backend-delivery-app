<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'driver_id',
        'total_price',
        'delivery_fee',
        'status',
        'payment_method',
        'order_timeout_at',
    ];


    protected $casts = ['status' => 'string'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

}
