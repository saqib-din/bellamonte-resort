<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    protected $fillable = ['food_category_id', 'name', 'description', 'price', 'is_available', 'image'];

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(FoodOrderItem::class, 'food_item_id');
    }
}
