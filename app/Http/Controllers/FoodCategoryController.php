<?php
namespace App\Http\Controllers;

use App\Models\FoodCategory;
use Illuminate\Http\Request;

class FoodCategoryController extends Controller
{
    public function index()
    {
        $categories = FoodCategory::withCount('items')->orderBy('sort_order')->get();
        return view('pages.admin-side.food.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
        ]);

        FoodCategory::create([
            'name'       => $request->name,
            'icon'       => $request->icon ?? '🍴',
            'is_active'  => $request->has('is_active'),
            'sort_order' => FoodCategory::count(),
        ]);

        return back()->with('success', 'Category added!');
    }

    public function update(Request $request, FoodCategory $foodCategory)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $foodCategory->update([
            'name'      => $request->name,
            'icon'      => $request->icon ?? '🍴',
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Category updated!');
    }

    public function destroy(FoodCategory $foodCategory)
    {
        if ($foodCategory->items()->count() > 0) {
            return back()->with('error', '"Please delete all items associated with this category before deleting the category."');
        }
        $foodCategory->delete();
        return back()->with('success', 'Category deleted.');
    }
}