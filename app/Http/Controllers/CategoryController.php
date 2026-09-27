<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(15);
        return view('products.categories', compact('categories'));
    }

    /**
     * AJAX endpoint: create a category on the fly from the "Add Product" page,
     * without leaving the page. Returns JSON so it can be appended to the select instantly.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'category' => $category,
                'message' => 'Category added.',
            ]);
        }

        return back()->with('success', 'Category added.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:50', 'unique:categories,name,'.$category->id],
            'color' => ['nullable', 'string', 'max:7'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update($request->only('name', 'color', 'is_active'));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'category' => $category]);
        }

        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete a category that still has products.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
