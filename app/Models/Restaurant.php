<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

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
        return $this->hasMany(Item::class)->withTrashed();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    // app/Models/Item.php

    public function drivers()
{
    return $this->hasMany(Driver::class);
}


public function getImageAttribute($value)
{
    return url('storage/restaurants/' . $value);
}


}
