<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FoodCategoryController extends Controller
{
    public function index()
    {
        $categories = FoodCategory::withCount('items')->orderBy('sort_order')->get()->map(fn ($c) => [
            'id'          => $c->id,
            'icon'        => $c->icon,
            'name'        => $c->name,
            'items_count' => $c->items_count,
            'is_active'   => (bool) $c->is_active,
        ]);

        return Inertia::render('Food/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
        ]);

        FoodCategory::create([
            'name'       => $request->name,
            'icon'       => $request->icon ?: '🍴',
            'is_active'  => $request->boolean('is_active'),
            'sort_order' => FoodCategory::count(),
        ]);

        return back()->with('success', 'Category added!');
    }

    public function update(Request $request, FoodCategory $foodCategory)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $foodCategory->update([
            'name'      => $request->name,
            'icon'      => $request->icon ?: '🍴',
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Category updated!');
    }

    public function destroy(FoodCategory $foodCategory)
    {
        if ($foodCategory->items()->count() > 0) {
            return back()->with('error', 'Please delete all items associated with this category before deleting the category.');
        }

        $foodCategory->delete();

        return back()->with('success', 'Category deleted.');
    }
}
