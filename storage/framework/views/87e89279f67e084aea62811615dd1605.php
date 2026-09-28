<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Receipt · <?php echo e($order->order_number); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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
        <a href="<?php echo e(route('orders.pos')); ?>" class="btn-secondary">
            ← Back
        </a>

        <button type="button" onclick="window.print()" class="btn-primary">
            🖨️ Print Receipt
        </button>

        <a href="<?php echo e(route('orders.pos')); ?>" class="btn-secondary">
            New Bill
        </a>

        <?php if($order->status === 'completed'): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('refund', $order)): ?>
                <button type="button" data-refund-order="<?php echo e($order->id); ?>" data-total="<?php echo e($order->total); ?>"
                    class="btn-danger">
                    ↩️ Refund
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div id="receipt" class="thermal-receipt bg-white shadow-sm print:shadow-none">

        <?php if($order->status === 'refunded'): ?>
            <div class="mb-3 p-2 border-2 border-black text-center text-xs">
                <p class="font-bold text-sm">*** REFUNDED ***</p>
                <p>
                    <?php echo e($settings['currency_symbol']); ?><?php echo e(number_format($order->refund_amount, 2)); ?>

                    on <?php echo e($order->refunded_at?->format('d-M-Y h:i A')); ?>

                </p>
                <?php if($order->refund_reason): ?>
                    <p class="mt-0.5">Reason: <?php echo e($order->refund_reason); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mb-3">
            <?php if(!empty($settings['logo'])): ?>
                <img src="<?php echo e(\Illuminate\Support\Facades\Storage::disk('public')->url($settings['logo'])); ?>"
                    class="h-10 mx-auto mb-1 object-contain filter grayscale contrast-200">
            <?php endif; ?>
            <p class="font-bold text-base uppercase leading-tight"><?php echo e($settings['business_name']); ?></p>
            <?php if(!empty($settings['address'])): ?>
                <p class="text-[11px] leading-tight mt-0.5"><?php echo e($settings['address']); ?></p>
            <?php endif; ?>
            <?php if(!empty($settings['phone'])): ?>
                <p class="text-[11px] leading-tight">Tel: <?php echo e($settings['phone']); ?></p>
            <?php endif; ?>
        </div>

        <div class="border-t border-b border-dashed border-black thermal-border py-1.5 my-2 text-[11px] space-y-0.5">
            <div class="flex justify-between"><span>Receipt #</span><span
                    class="font-bold"><?php echo e($order->order_number); ?></span></div>
            <div class="flex justify-between">
                <span>Date</span><span><?php echo e($order->completed_at?->format('d-M-Y h:i A') ?? $order->created_at->format('d-M-Y h:i A')); ?></span>
            </div>
            <div class="flex justify-between">
                <span>Type</span><span><?php echo e($order->diningTable ? 'Dine In - ' . $order->diningTable->name : 'Takeaway'); ?></span>
            </div>
            <?php if($order->customer): ?>
                <div class="flex justify-between"><span>Customer</span><span><?php echo e($order->customer->name); ?></span></div>
            <?php endif; ?>
            <div class="flex justify-between"><span>Cashier</span><span><?php echo e($order->cashier->name ?? '-'); ?></span></div>
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
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b border-dotted border-gray-300 print:border-none">
                        <td class="py-0.5 align-top">
                            <?php echo e($item->product_name); ?>

                            <?php if($item->note): ?>
                                <br><span class="text-[10px] italic">· <?php echo e($item->note); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center py-0.5 align-top"><?php echo e($item->quantity); ?></td>
                        <td class="text-right py-0.5 align-top"><?php echo e(number_format($item->price, 2)); ?></td>
                        <td class="text-right py-0.5 align-top"><?php echo e(number_format($item->subtotal, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="border-t border-dashed border-black thermal-border pt-1.5 space-y-0.5 text-[11px] no-break">
            <div class="flex justify-between">
                <span>Subtotal</span><span><?php echo e($settings['currency_symbol']); ?><?php echo e(number_format($order->subtotal, 2)); ?></span>
            </div>
            <?php if($order->discount > 0): ?>
                <?php
                    $discountDeduction = round($order->subtotal * ($order->discount / 100), 2);
                ?>
                <div class="flex justify-between">
                    <span>Discount (<?php echo e(rtrim(rtrim(number_format($order->discount, 2), '0'), '.')); ?>%)</span>
                    <span>-<?php echo e($settings['currency_symbol']); ?><?php echo e(number_format($discountDeduction, 2)); ?></span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between">
                <span>Tax (<?php echo e(rtrim(rtrim(number_format($order->tax_percent, 2), '0'), '.')); ?>%)</span>
                <span><?php echo e($settings['currency_symbol']); ?><?php echo e(number_format($order->tax_amount, 2)); ?></span>
            </div>
            <div
                class="flex justify-between font-bold text-sm border-t border-dashed border-black thermal-border mt-1 pt-1">
                <span>TOTAL</span><span><?php echo e($settings['currency_symbol']); ?><?php echo e(number_format($order->total, 2)); ?></span>
            </div>
            <?php if($order->status === 'refunded'): ?>
                <div class="flex justify-between font-bold">
                    <span>Refunded</span><span>-<?php echo e($settings['currency_symbol']); ?><?php echo e(number_format($order->refund_amount, 2)); ?></span>
                </div>
                <div
                    class="flex justify-between font-bold border-t border-dashed border-black thermal-border mt-1 pt-1">
                    <span>NET</span><span><?php echo e($settings['currency_symbol']); ?><?php echo e(number_format($order->net_total, 2)); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="border-t border-dashed border-black thermal-border mt-1.5 pt-1.5 space-y-0.5 text-[11px] no-break">
            <div class="flex justify-between">
                <span>Payment</span><span class="capitalize"><?php echo e($order->payment_method); ?></span>
            </div>
            <div class="flex justify-between">
                <span>Paid</span><span><?php echo e($settings['currency_symbol']); ?><?php echo e(number_format($order->paid_amount, 2)); ?></span>
            </div>
            <div class="flex justify-between">
                <span>Change</span><span><?php echo e($settings['currency_symbol']); ?><?php echo e(number_format($order->change_amount, 2)); ?></span>
            </div>
        </div>

        <?php if(!empty($settings['receipt_footer'])): ?>
            <div class="text-center mt-3 pt-1 border-t border-dashed border-black thermal-border text-[11px] no-break">
                <p><?php echo e($settings['receipt_footer']); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <?php echo $__env->make('orders.partials._refund_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/orders/receipt.blade.php ENDPATH**/ ?>