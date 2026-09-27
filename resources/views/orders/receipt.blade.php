<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt · {{ $order->order_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Courier New', monospace;
        }
    </style>
</head>

<body class="bg-gray-100 py-8 print:bg-white print:py-0">

    <div class="max-w-sm mx-auto no-print flex justify-center gap-2 mb-4">

        <a href="{{ route('tables.index') }}" class="btn-secondary">
            ← Back
        </a>

        <button type="button" onclick="window.print()" class="btn-primary">
            🖨️ Print Receipt
        </button>

        <a href="{{ route('orders.pos') }}" class="btn-secondary">
            New Bill
        </a>

    </div>

    <div id="receipt" class="max-w-sm mx-auto bg-white p-6 shadow-sm print:shadow-none text-[13px] leading-relaxed">

        <div class="text-center mb-4">
            @if (!empty($settings['logo']))
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['logo']) }}"
                    class="h-12 mx-auto mb-2 object-contain">
            @endif
            <p class="font-bold text-lg">{{ $settings['business_name'] }}</p>
            @if (!empty($settings['address']))
                <p>{{ $settings['address'] }}</p>
            @endif
            @if (!empty($settings['phone']))
                <p>Tel: {{ $settings['phone'] }}</p>
            @endif
        </div>

        <div class="border-t border-b border-dashed border-gray-400 py-2 my-2 space-y-0.5">
            <div class="flex justify-between"><span>Receipt #</span><span>{{ $order->order_number }}</span></div>
            <div class="flex justify-between">
                <span>Date</span><span>{{ $order->completed_at?->format('d-M-Y h:i A') ?? $order->created_at->format('d-M-Y h:i A') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Type</span><span>{{ $order->diningTable ? 'Dine In - ' . $order->diningTable->name : 'Takeaway' }}</span>
            </div>
            @if ($order->customer)
                <div class="flex justify-between"><span>Customer</span><span>{{ $order->customer->name }}</span></div>
            @endif
            <div class="flex justify-between"><span>Cashier</span><span>{{ $order->cashier->name ?? '-' }}</span></div>
        </div>

        <table class="w-full my-2">
            <thead>
                <tr class="border-b border-dashed border-gray-400">
                    <th class="text-left py-1">Item</th>
                    <th class="text-center py-1">Qty</th>
                    <th class="text-right py-1">Price</th>
                    <th class="text-right py-1">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td class="py-1 align-top">
                            {{ $item->product_name }}
                            @if ($item->note)
                                <br><span class="text-gray-400 text-xxs">· {{ $item->note }}</span>
                            @endif
                        </td>
                        <td class="text-center py-1 align-top">{{ $item->quantity }}</td>
                        <td class="text-right py-1 align-top">{{ number_format($item->price, 2) }}</td>
                        <td class="text-right py-1 align-top">{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="border-t border-dashed border-gray-400 pt-2 space-y-0.5">
            <div class="flex justify-between">
                <span>Subtotal</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if ($order->discount > 0)
                <div class="flex justify-between">
                    <span>Discount</span><span>-{{ $settings['currency_symbol'] }}{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between"><span>Tax
                    ({{ rtrim(rtrim(number_format($order->tax_percent, 2), '0'), '.') }}%)</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-base border-t border-dashed border-gray-400 mt-1 pt-1">
                <span>TOTAL</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <div class="border-t border-dashed border-gray-400 mt-2 pt-2 space-y-0.5">
            <div class="flex justify-between"><span>Payment</span><span
                    class="capitalize">{{ $order->payment_method }}</span></div>
            <div class="flex justify-between">
                <span>Paid</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->paid_amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Change</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->change_amount, 2) }}</span>
            </div>
        </div>

        <div class="text-center mt-4 pt-2 border-t border-dashed border-gray-400">
            <p>{{ $settings['receipt_footer'] }}</p>
        </div>
    </div>
</body>

</html>
