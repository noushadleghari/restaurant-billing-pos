<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Receipt · {{ $order->order_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Base Screen & Typography */
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Screen preview box */
        .thermal-receipt {
            width: 72mm;
            max-width: 100%;
            margin: 0 auto;
            padding: 4mm 2mm;
            font-size: 12px;
            line-height: 1.25;
            word-break: break-word;
        }

        /* -------------------------------------------------------------
           Print Engine Rules (Forces browser away from A4 onto 80mm Roll)
           ------------------------------------------------------------- */
        @page {
            /* Fixes the virtual paper to an 80mm roll with dynamic height */
            size: 80mm auto;
            margin: 0mm; /* Clears browser headers/footers (URL, dates) */
        }

        @media print {
            html, body {
                width: 80mm !important;
                min-width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
            }

            .no-print {
                display: none !important;
            }

            .thermal-receipt {
                width: 72mm !important; /* Printable head width inside 80mm paper */
                max-width: 72mm !important;
                margin: 0 auto !important;
                /* Bottom padding gives cutter clearance so it won't cut the footer */
                padding: 2mm 1mm 12mm 1mm !important;
                box-shadow: none !important;
                border: none !important;
            }

            /* Prevent table rows and summaries from breaking across pages */
            tr, .no-break {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            /* Force crisp black borders for thermal heads */
            .thermal-border {
                border-color: #000000 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 py-8 print:bg-white print:py-0">

    <div class="max-w-sm mx-auto no-print flex justify-center gap-2 mb-4 flex-wrap">
        <a href="{{ route('orders.pos') }}" class="btn-secondary">
            ← Back
        </a>

        <button type="button" onclick="window.print()" class="btn-primary">
            🖨️ Print Receipt
        </button>

        <a href="{{ route('orders.pos') }}" class="btn-secondary">
            New Bill
        </a>

        @if ($order->status === 'completed')
            @can('refund', $order)
                <button type="button" data-refund-order="{{ $order->id }}" data-total="{{ $order->total }}"
                    class="btn-danger">
                    ↩️ Refund
                </button>
            @endcan
        @endif
    </div>

    <div id="receipt" class="thermal-receipt bg-white shadow-sm print:shadow-none">

        @if ($order->status === 'refunded')
            <div class="mb-3 p-2 border-2 border-black text-center text-xs">
                <p class="font-bold text-sm">*** REFUNDED ***</p>
                <p>
                    {{ $settings['currency_symbol'] }}{{ number_format($order->refund_amount, 2) }}
                    on {{ $order->refunded_at?->format('d-M-Y h:i A') }}
                </p>
                @if ($order->refund_reason)
                    <p class="mt-0.5">Reason: {{ $order->refund_reason }}</p>
                @endif
            </div>
        @endif

        <div class="text-center mb-3">
            @if (!empty($settings['logo']))
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['logo']) }}"
                    class="h-10 mx-auto mb-1 object-contain filter grayscale contrast-200">
            @endif
            <p class="font-bold text-base uppercase leading-tight">{{ $settings['business_name'] }}</p>
            @if (!empty($settings['address']))
                <p class="text-[11px] leading-tight mt-0.5">{{ $settings['address'] }}</p>
            @endif
            @if (!empty($settings['phone']))
                <p class="text-[11px] leading-tight">Tel: {{ $settings['phone'] }}</p>
            @endif
        </div>

        <div class="border-t border-b border-dashed border-black thermal-border py-1.5 my-2 text-[11px] space-y-0.5">
            <div class="flex justify-between"><span>Receipt #</span><span
                    class="font-bold">{{ $order->order_number }}</span></div>
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

        <table class="w-full my-2 text-[11px]">
            <thead>
                <tr class="border-b border-dashed border-black thermal-border font-bold">
                    <th class="text-left py-1 w-[46%]">Item</th>
                    <th class="text-center py-1 w-[14%]">Qty</th>
                    <th class="text-right py-1 w-[20%]">Price</th>
                    <th class="text-right py-1 w-[20%]">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr class="border-b border-dotted border-gray-300 print:border-none">
                        <td class="py-0.5 align-top">
                            {{ $item->product_name }}
                            @if ($item->note)
                                <br><span class="text-[10px] italic">· {{ $item->note }}</span>
                            @endif
                        </td>
                        <td class="text-center py-0.5 align-top">{{ $item->quantity }}</td>
                        <td class="text-right py-0.5 align-top">{{ number_format($item->price, 2) }}</td>
                        <td class="text-right py-0.5 align-top">{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="border-t border-dashed border-black thermal-border pt-1.5 space-y-0.5 text-[11px] no-break">
            <div class="flex justify-between">
                <span>Subtotal</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if ($order->discount > 0)
                @php
                    $discountDeduction = round($order->subtotal * ($order->discount / 100), 2);
                @endphp
                <div class="flex justify-between">
                    <span>Discount ({{ rtrim(rtrim(number_format($order->discount, 2), '0'), '.') }}%)</span>
                    <span>-{{ $settings['currency_symbol'] }}{{ number_format($discountDeduction, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span>Tax ({{ rtrim(rtrim(number_format($order->tax_percent, 2), '0'), '.') }}%)</span>
                <span>{{ $settings['currency_symbol'] }}{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            <div
                class="flex justify-between font-bold text-sm border-t border-dashed border-black thermal-border mt-1 pt-1">
                <span>TOTAL</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->total, 2) }}</span>
            </div>
            @if ($order->status === 'refunded')
                <div class="flex justify-between font-bold">
                    <span>Refunded</span><span>-{{ $settings['currency_symbol'] }}{{ number_format($order->refund_amount, 2) }}</span>
                </div>
                <div
                    class="flex justify-between font-bold border-t border-dashed border-black thermal-border mt-1 pt-1">
                    <span>NET</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->net_total, 2) }}</span>
                </div>
            @endif
        </div>

        <div class="border-t border-dashed border-black thermal-border mt-1.5 pt-1.5 space-y-0.5 text-[11px] no-break">
            <div class="flex justify-between">
                <span>Payment</span><span class="capitalize">{{ $order->payment_method }}</span>
            </div>
            <div class="flex justify-between">
                <span>Paid</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->paid_amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Change</span><span>{{ $settings['currency_symbol'] }}{{ number_format($order->change_amount, 2) }}</span>
            </div>
        </div>

        @if (!empty($settings['receipt_footer']))
            <div class="text-center mt-3 pt-1 border-t border-dashed border-black thermal-border text-[11px] no-break">
                <p>{{ $settings['receipt_footer'] }}</p>
            </div>
        @endif
    </div>

    @include('orders.partials._refund_modal')
</body>

</html>