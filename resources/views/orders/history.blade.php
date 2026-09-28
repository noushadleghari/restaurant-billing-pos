@extends('layouts.app')
@section('title', 'Order History')

@section('content')
<div class="p-4 lg:p-8 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Order History</h1>
            <p class="text-gray-400 text-sm">All bills, held and completed</p>
        </div>
    </div>

    <form method="GET" class="flex flex-wrap items-center gap-3 mb-5">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="🔍 Search order #..." class="input max-w-[200px]">
        <select name="status" class="input max-w-[160px]" onchange="this.form.submit()">
            <option value="">All statuses</option>
            <option value="open" @selected(request('status')==='open')>Open / Held</option>
            <option value="completed" @selected(request('status')==='completed')>Completed</option>
            <option value="refunded" @selected(request('status')==='refunded')>Refunded</option>
            <option value="cancelled" @selected(request('status')==='cancelled')>Cancelled</option>
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="input max-w-[160px]" onchange="this.form.submit()">
        <button class="btn-secondary">Filter</button>
    </form>

    <div class="card overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Order #</th>
                    <th class="text-left px-5 py-3">Table</th>
                    <th class="text-left px-5 py-3">Cashier</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-left px-5 py-3">Total</th>
                    <th class="text-left px-5 py-3">Date</th>
                    <th class="text-right px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $order->order_number }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $order->diningTable?->name ?? 'Takeaway' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $order->cashier?->name ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="{{ match($order->status) { 'completed' => 'badge-green', 'open' => 'badge-amber', 'refunded' => 'badge-red', default => 'badge-gray' } }}"
                                  @if($order->status === 'cancelled' && $order->cancel_reason) title="{{ $order->cancel_reason }}" @endif
                                  @if($order->status === 'refunded' && $order->refund_reason) title="{{ $order->refund_reason }}" @endif>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-semibold text-gray-800">{{ \App\Models\Setting::get('currency_symbol','$') }}{{ number_format($order->net_total, 2) }}</p>
                            @if($order->status === 'refunded')
                                <p class="text-xxs text-red-500">-{{ \App\Models\Setting::get('currency_symbol','$') }}{{ number_format($order->refund_amount, 2) }} refunded</p>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-400">{{ $order->created_at->format('d M, h:i A') }}</td>
                        <td class="px-5 py-3 text-right space-x-3 whitespace-nowrap">
                            @if($order->status === 'completed')
                                <a href="{{ route('orders.receipt', $order) }}" target="_blank" class="text-brand-600 hover:underline text-xs font-medium">Receipt</a>
                                @can('refund', $order)
                                    <button type="button" data-refund-order="{{ $order->id }}" data-total="{{ $order->total }}"
                                            class="text-red-500 hover:underline text-xs font-medium">Refund</button>
                                @endcan
                            @elseif($order->status === 'open')
                                <a href="{{ route('orders.pos', ['table' => $order->dining_table_id]) }}" class="text-brand-600 hover:underline text-xs font-medium">Continue</a>
                                @can('cancel', $order)
                                    <button type="button" data-cancel-order="{{ $order->id }}" class="text-red-500 hover:underline text-xs font-medium">Cancel</button>
                                @endcan
                            @elseif($order->status === 'refunded')
                                <a href="{{ route('orders.receipt', $order) }}" target="_blank" class="text-brand-600 hover:underline text-xs font-medium">Receipt</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>

@include('orders.partials._cancel_modal')
@include('orders.partials._refund_modal')
@endsection
