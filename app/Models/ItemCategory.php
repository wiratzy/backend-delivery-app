<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function items()
    {
        return $this->hasMany(Item::class, 'item_category_id');
    }

    public function getImageAttribute($value)
{
    return url('storage/item_categories/' . $value);
}
}
