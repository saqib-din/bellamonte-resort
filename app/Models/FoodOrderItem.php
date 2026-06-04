<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodOrderItem extends Model
{
    protected $fillable = ['food_order_id', 'food_item_id', 'item_name', 'unit_price', 'quantity', 'subtotal'];

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class);
    }

    public function order()
    {
        return $this->belongsTo(FoodOrder::class, 'food_order_id');
    }
}
