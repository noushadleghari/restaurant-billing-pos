@extends('layouts.app')
@section('title', 'Add Product')

@section('content')
<div class="p-4 lg:p-8 max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Back to Products</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-1">Add New Product</h1>
    </div>

    <form id="product-form" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" novalidate>
        @include('products.partials._form', ['mode' => 'create'])
    </form>
</div>

<script>
    window.routes = {
        categoriesStore: @json(route('categories.store')),
        productsIndex: @json(route('products.index')),
    };
</script>
@endsection
