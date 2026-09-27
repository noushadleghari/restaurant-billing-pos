<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($products as $product)
        <div class="card overflow-hidden {{ $product->is_available ? '' : 'unavailable' }}" data-product-card>
            <img src="{{ $product->image_url }}" class="w-full h-32 object-cover bg-gray-100" loading="lazy" alt="{{ $product->name }}">
            <div class="p-3">
                <p class="text-xs text-brand-600 font-semibold uppercase tracking-wide">{{ $product->category->name }}</p>
                <p class="font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                <p class="text-brand-700 font-bold">{{ \App\Models\Setting::get('currency_symbol', '$') }}{{ number_format($product->price, 2) }}</p>

                <div class="flex items-center gap-1.5 mt-3">
                    <a href="{{ route('products.edit', $product) }}" class="btn-secondary flex-1 !py-1.5 !text-xs">Edit</a>
                    <button type="button" data-toggle-availability="{{ $product->id }}"
                            class="btn-secondary !py-1.5 !text-xs {{ $product->is_available ? '' : 'bg-red-50 text-red-600 border-red-200' }}">
                        {{ $product->is_available ? 'Available' : 'Out of stock' }}
                    </button>
                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="button" data-delete-product class="btn-secondary !py-1.5 !px-2 !text-xs text-red-500">🗑</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-16 text-gray-400">
            <p class="text-4xl mb-2">📦</p>
            <p>No products found. <a href="{{ route('products.create') }}" class="text-brand-600 font-medium">Add your first product</a>.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $products->links() }}</div>
