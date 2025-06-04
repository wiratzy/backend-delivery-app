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
        'owner_id'
    ];


    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


}
