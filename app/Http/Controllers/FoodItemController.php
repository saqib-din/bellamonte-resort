<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FoodItemController extends Controller
{
    public function index()
    {
        $items = FoodItem::with('category')->latest()->get()->map(fn ($i) => [
            'id'               => $i->id,
            'name'             => $i->name,
            'description'      => $i->description,
            'price'            => $i->price,
            'is_available'     => (bool) $i->is_available,
            'food_category_id' => $i->food_category_id,
            'category_name'    => $i->category->name ?? '—',
            'category_icon'    => $i->category->icon ?? '',
        ]);

        $categories = FoodCategory::where('is_active', true)->orderBy('sort_order')->get(['id', 'name', 'icon']);

        return Inertia::render('Food/Items/Index', [
            'items'      => $items,
            'categories' => $categories,
        ]);
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
            'is_available'     => $request->boolean('is_available'),
        ]);

        return back()->with('success', 'Item added!');
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        $request->validate([
            'food_category_id' => 'required|exists:food_categories,id',
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
        ]);

        $foodItem->update([
            'food_category_id' => $request->food_category_id,
            'name'             => $request->name,
            'description'      => $request->description,
            'price'            => $request->price,
            'is_available'     => $request->boolean('is_available'),
        ]);

        return back()->with('success', 'Item updated!');
    }

    public function destroy(FoodItem $foodItem)
    {
        if ($foodItem->orderItems()->exists()) {
            $foodItem->update(['is_available' => false]);

            return back()->with('error', 'Item is used in orders, so it has been marked unavailable.');
        }

        $foodItem->delete();

        return back()->with('success', 'Item deleted successfully.');
    }
}
