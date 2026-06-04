<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    protected $fillable = ['name', 'icon', 'is_active', 'sort_order'];

    public function items()
    {
        return $this->hasMany(FoodItem::class);
    }

    public function availableItems()
    {
        return $this->hasMany(FoodItem::class)->where('is_available', true);
    }
}
