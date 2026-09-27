@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="p-4 lg:p-8 max-w-7xl mx-auto">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Products</h1>
            <p class="text-gray-400 text-sm">Manage your menu items</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('categories.index') }}" class="btn-secondary">🏷️ Categories</a>
            <a href="{{ route('products.create') }}" class="btn-primary">+ Add Product</a>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3 mb-5">
        <input type="text" id="product-search" placeholder="🔍 Search products or SKU..." class="input max-w-xs">
        <select id="category-filter" class="input max-w-[180px]">
            <option value="">All categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div id="product-grid-wrapper">
        @include('products.partials._grid', ['products' => $products])
    </div>
</div>
@endsection
