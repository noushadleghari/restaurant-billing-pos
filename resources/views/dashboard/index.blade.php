@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="p-4 lg:p-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Good day 👋</h1>
            <p class="text-gray-400 text-sm">Here's how things look today, {{ now()->format('D, M j') }}</p>
        </div>
        <a href="{{ route('orders.pos') }}" class="btn-primary btn-lg">🧾 New Bill</a>
    </div>

    {{-- Quick stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Today's Sales</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\Setting::get('currency_symbol', '$') }}{{ number_format($todaySummary['total_sales'], 2) }}</p>
            <p class="text-xs mt-1 {{ $todaySummary['total_sales'] >= $yesterdaySummary['total_sales'] ? 'text-green-600' : 'text-red-500' }}">
                vs yesterday {{ \App\Models\Setting::get('currency_symbol', '$') }}{{ number_format($yesterdaySummary['total_sales'], 2) }}
            </p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Orders Today</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $todaySummary['total_orders'] }}</p>
            <p class="text-xs mt-1 text-gray-400">Avg {{ \App\Models\Setting::get('currency_symbol', '$') }}{{ number_format($todaySummary['avg_order_value'], 2) }}/order</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Tables Occupied</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $occupiedTables }} / {{ $totalTables }}</p>
            <p class="text-xs mt-1 text-gray-400">{{ $openOrders }} open orders</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Out of Stock</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $lowAvailability }}</p>
            <p class="text-xs mt-1 text-gray-400">products marked unavailable</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Recent orders --}}
        <div class="lg:col-span-2 card p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Recent Completed Orders</h2>
                <a href="{{ route('orders.history') }}" class="text-sm text-brand-600 font-medium hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $order->diningTable?->name ?? 'Takeaway' }} · {{ $order->completed_at?->diffForHumans() }}
                            </p>
                        </div>
                        <p class="font-bold text-gray-800">{{ \App\Models\Setting::get('currency_symbol', '$') }}{{ number_format($order->total, 2) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 py-8 text-center">No completed orders yet today.</p>
                @endforelse
            </div>
        </div>

        {{-- Top products --}}
        <div class="card p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Top Sellers Today</h2>
            <div class="space-y-3">
                @forelse($topProducts as $p)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 truncate">{{ $p->product_name }}</span>
                        <span class="font-semibold text-gray-800">×{{ $p->total_qty }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-6">No sales yet today.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
