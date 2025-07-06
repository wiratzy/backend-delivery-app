<?php
// app/Models/User.php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'photo',
        'phone',
        'email',
        'password',
        'role',
        'is_active_driver',
        'fcm_token',
        'address_longitude',
        'address_latitude'
    ];

    protected $hidden = ['password', 'remember_token'];


    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'owner_id');
    }

    public function driverOrders()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }


    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // app/Models/Item.php

    public function getPhotoAttribute($value)
    {
        return url('storage/photos/' . $value);
    }

}
