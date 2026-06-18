<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FoodItemController extends Controller
{
    public function index(Request $request)
    {
        $sortable = ['name', 'price', 'is_available', 'created_at'];

        $query = FoodItem::with('category');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('description', 'like', "%{$s}%"));
        }

        $sort = in_array($request->sort, $sortable, true) ? $request->sort : 'created_at';
        $dir  = $request->dir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $dir);

        $perPage = (int) $request->input('per_page', 15);

        $items = $query->paginate($perPage)->withQueryString()->through(fn ($i) => [
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
            'filters'    => [
                'search'   => $request->search,
                'sort'     => $sort,
                'dir'      => $dir,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_category_id' => 'required|exists:food_categories,id',
            'name'             => 'required|string|max:50',
            'description'      => 'nullable|string|max:1000',
            'price'            => 'required|numeric|min:0|max:9999999',
            'is_available'     => 'nullable|boolean',
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
            'name'             => 'required|string|max:50',
            'description'      => 'nullable|string|max:1000',
            'price'            => 'required|numeric|min:0|max:9999999',
            'is_available'     => 'nullable|boolean',
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
