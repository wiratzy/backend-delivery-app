<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'item_id', 'quantity', 'price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function item()
{
    return $this->belongsTo(Item::class)->with('restaurant');
}

}
