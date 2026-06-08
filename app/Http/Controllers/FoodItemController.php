<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\FoodCategory;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    public function index()
    {
        $items      = FoodItem::with('category')->latest()->get();
        $categories = FoodCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('pages.admin-side.food.items.index', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_category_id' => 'required|exists:food_categories,id',
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
        ]);

        FoodItem::create([
            'food_category_id' => $request->food_category_id,
            'name'             => $request->name,
            'description'      => $request->description,
            'price'            => $request->price,
            'is_available'     => $request->has('is_available') ? 1 : 0,
        ]);

        return back()->with('success', 'Item added!');
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $foodItem->update([
            'food_category_id' => $request->food_category_id,
            'name'             => $request->name,
            'description'      => $request->description,
            'price'            => $request->price,
            'is_available'     => $request->has('is_available') ? 1 : 0,
        ]);

        return back()->with('success', 'Item updated!');
    }

    public function destroy(FoodItem $foodItem)
    {
        if ($foodItem->orderItems()->exists()) {

            $foodItem->update([
                'is_available' => false
            ]);

            return back()->with(
                'error',
                'Item is used in orders, so it has been marked unavailable.'
            );
        }

        $foodItem->delete();

        return back()->with('success', 'Item deleted successfully.');
    }
}
