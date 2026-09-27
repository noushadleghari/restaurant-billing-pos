<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $products = Product::with('category')
            ->search($request->get('q'))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        if ($request->wantsJson()) {
            return response()->json(['html' => view('products.partials._grid', compact('products'))->render()]);
        }

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request->validated(), $request->file('image'));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'product' => $product, 'redirect' => route('products.index')]);
        }

        return redirect()->route('products.index')->with('success', "\"{$product->name}\" was added successfully.");
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->update($product, $request->validated(), $request->file('image'));

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'product' => $product]);
        }

        return redirect()->route('products.index')->with('success', "\"{$product->name}\" was updated.");
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Product deleted.');
    }

    /**
     * AJAX: quick toggle "available / out of stock" from the product grid, no page reload.
     */
    public function toggleAvailability(Product $product)
    {
        $product->update(['is_available' => !$product->is_available]);

        return response()->json(['success' => true, 'is_available' => $product->is_available]);
    }

    /**
     * AJAX: live product search/filter used by the POS billing screen.
     */
    public function posSearch(Request $request)
    {
        $products = Product::with('category')
            ->where('is_available', true)
            ->search($request->get('q'))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->orderBy('name')
            ->limit(60)
            ->get();

        return response()->json(['products' => $products]);
    }
}
