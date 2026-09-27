@extends('layouts.app')
@section('title', 'Billing')

@section('content')
<div id="pos-screen"
    class="min-h-screen lg:h-screen flex flex-col lg:flex-row pt-14 lg:pt-0 -mt-14 lg:mt-0">
        {{-- LEFT: products --}}
        <div class="flex-1 flex flex-col min-w-0 p-4 lg:p-6 overflow-hidden">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <a href="{{ route('tables.index') }}" class="text-xs text-gray-400 hover:text-gray-600">← Back to
                        Tables</a>
                    <h1 class="text-xl font-bold text-gray-800">
                        {{ $table ? 'Table ' . $table->name : 'Manual / Takeaway Bill' }}
                    </h1>
                </div>
                <input type="text" id="pos-product-search" placeholder="🔍 Search menu..." class="input max-w-[220px]">
            </div>

            <div id="pos-category-pills" class="flex items-center gap-2 overflow-x-auto pb-3 mb-2 no-print">
                <input type="hidden" id="pos-active-category" value="">
                <span class="category-pill active" data-category-id="">All Items</span>
                @foreach ($categories as $cat)
                    <span class="category-pill" data-category-id="{{ $cat->id }}">{{ $cat->name }}</span>
                @endforeach
            </div>

            <div class="flex-1 overflow-y-auto">
                <div id="pos-product-grid" class="grid grid-cols-3 sm:grid-cols-4 xl:grid-cols-5 gap-3">
                    @foreach ($products as $p)
                        <div class="product-tile {{ $p->is_available ? '' : 'unavailable' }}" data-add-product
                            data-id="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->price }}">
                            <img src="{{ $p->image_url }}" class="w-full h-20 object-cover bg-gray-100" loading="lazy"
                                alt="{{ $p->name }}">
                            <div class="p-2 text-center">
                                <p class="text-xs font-semibold text-gray-800 truncate">{{ $p->name }}</p>
                                <p class="text-xs text-brand-600 font-bold">
                                    {{ $settings['currency_symbol'] }}{{ number_format($p->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT: cart / checkout panel --}}
        <div class="w-full lg:w-[380px] shrink-0 bg-white border-t lg:border-t-0 lg:border-l border-gray-200 flex flex-col">
            <div class="p-4 border-b border-gray-100">
                <div class="grid grid-cols-2 gap-2 mb-3">
                    @php $orderType = $activeOrder->order_type ?? ($table ? 'dine_in' : 'takeaway'); @endphp
                    <select id="order-type-select" class="input !py-2 !text-sm">
                        <option value="dine_in" @selected($orderType === 'dine_in')>Dine In</option>
                        <option value="takeaway" @selected($orderType === 'takeaway')>Takeaway</option>
                    </select>
                    <div id="table-select-wrapper" class="{{ $orderType === 'dine_in' ? '' : 'hidden' }}">
                        <select id="table-select" class="input !py-2 !text-sm">
                            <option value="">No table</option>
                            @foreach ($tables as $t)
                                <option value="{{ $t->id }}" @selected($table && $table->id === $t->id)>{{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="relative">
                    <input type="text" id="customer-search-input"
                        placeholder="👤 Customer (optional, search by name/phone)" class="input !py-2 !text-sm"
                        value="{{ $activeOrder?->customer?->name }}">
                    <input type="hidden" id="customer_id" value="{{ $activeOrder?->customer_id }}">
                    <div id="customer-results"
                        class="hidden absolute z-20 top-full mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                    </div>
                </div>
            </div>

            <div id="cart-items" class="flex-1 overflow-y-auto px-4"></div>

            <div class="p-4 border-t border-gray-100 space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs text-gray-400">Discount</label>
                        <input type="text" id="discount-input" value="{{ $activeOrder->discount ?? 0 }}"
                            class="input !py-2 !text-sm" inputmode="decimal">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Tax %</label>
                        <input type="text" id="tax-input"
                            value="{{ $activeOrder->tax_percent ?? $settings['tax_percent'] }}"
                            class="input !py-2 !text-sm" inputmode="decimal">
                    </div>
                </div>
                <textarea id="order-note" placeholder="Note (optional)" class="input !py-2 !text-sm" rows="1">{{ $activeOrder?->note }}</textarea>

                <div class="space-y-1 text-sm border-t border-gray-100 pt-3">
                    <div class="flex justify-between text-gray-500"><span>Subtotal</span><span
                            id="sum-subtotal">{{ $settings['currency_symbol'] }}0.00</span></div>
                    <div class="flex justify-between text-gray-500"><span>Tax</span><span
                            id="sum-tax">{{ $settings['currency_symbol'] }}0.00</span></div>
                    <div class="flex justify-between text-lg font-bold text-gray-800"><span>Total</span><span
                            id="sum-total">{{ $settings['currency_symbol'] }}0.00</span></div>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-1">
                    <button id="save-order-btn" type="button"
                        class="btn-secondary btn-lg flex-1 {{ $orderType === 'takeaway' ? 'hidden' : '' }}" disabled>
                        💾 Save
                    </button>
                    <button id="checkout-btn" type="button" class="btn-primary btn-lg" disabled>💳 Checkout</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Checkout modal --}}
    <div id="checkout-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div id="checkout-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
        <div class="card relative w-full max-w-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">Complete Payment</h3>

            <p class="text-3xl font-bold text-brand-700 text-center mb-4" id="modal-total">$0.00</p>

            <div class="mb-4">
                <label class="label">Payment Method</label>
                <select id="payment-method-select" class="input">
                    <option value="cash">💵 Cash</option>
                    <option value="card">💳 Card</option>
                    <option value="online">📱 Online / Wallet</option>
                </select>
            </div>
            <div class="mb-2">
                <label class="label">Amount Received</label>
                <input type="text" id="paid-amount-input" class="input" inputmode="decimal">
            </div>
            <p class="text-sm text-gray-500 mb-5">Change: <span id="modal-change"
                    class="font-bold text-gray-800">$0.00</span></p>

            <div class="flex gap-3">
                <button id="confirm-checkout-btn" type="button" class="btn-primary flex-1">Confirm Payment</button>
                <button id="close-checkout-modal" type="button" class="btn-secondary flex-1">Cancel</button>
            </div>
        </div>
    </div>

    @php
        $initialItems = $activeOrder
            ? $activeOrder->items
                ->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'name' => $item->product_name,
                        'price' => (float) $item->price,
                        'quantity' => $item->quantity,
                        'note' => $item->note,
                    ];
                })
                ->values()
            : [];
    @endphp
    <script>
        window.routes = {
            productSearch: @json(route('products.pos-search')),
            customerSearch: @json(route('customers.search')),
            orderSave: @json(route('orders.save')),
            tables: @json(route('tables.index')),
        };

        window.posConfig = {
            currencySymbol: @json($settings['currency_symbol']),
            orderId: @json($activeOrder?->id),
            tableId: @json($table?->id),
            initialItems: @json($initialItems),
        };
    </script>
@endsection
