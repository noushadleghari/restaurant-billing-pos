<?php $__env->startSection('title', 'Order History'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Order History</h1>
            <p class="text-gray-400 text-sm">All bills, held and completed</p>
        </div>
    </div>

    <form method="GET" class="flex flex-wrap items-center gap-3 mb-5">
        <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="🔍 Search order #..." class="input max-w-[200px]">
        <select name="status" class="input max-w-[160px]" onchange="this.form.submit()">
            <option value="">All statuses</option>
            <option value="open" <?php if(request('status')==='open'): echo 'selected'; endif; ?>>Open / Held</option>
            <option value="completed" <?php if(request('status')==='completed'): echo 'selected'; endif; ?>>Completed</option>
            <option value="refunded" <?php if(request('status')==='refunded'): echo 'selected'; endif; ?>>Refunded</option>
            <option value="cancelled" <?php if(request('status')==='cancelled'): echo 'selected'; endif; ?>>Cancelled</option>
        </select>
        <input type="date" name="date" value="<?php echo e(request('date')); ?>" class="input max-w-[160px]" onchange="this.form.submit()">
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
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800"><?php echo e($order->order_number); ?></td>
                        <td class="px-5 py-3 text-gray-500"><?php echo e($order->diningTable?->name ?? 'Takeaway'); ?></td>
                        <td class="px-5 py-3 text-gray-500"><?php echo e($order->cashier?->name ?? '-'); ?></td>
                        <td class="px-5 py-3">
                            <span class="<?php echo e(match($order->status) { 'completed' => 'badge-green', 'open' => 'badge-amber', 'refunded' => 'badge-red', default => 'badge-gray' }); ?>"
                                  <?php if($order->status === 'cancelled' && $order->cancel_reason): ?> title="<?php echo e($order->cancel_reason); ?>" <?php endif; ?>
                                  <?php if($order->status === 'refunded' && $order->refund_reason): ?> title="<?php echo e($order->refund_reason); ?>" <?php endif; ?>>
                                <?php echo e(ucfirst($order->status)); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-semibold text-gray-800"><?php echo e(\App\Models\Setting::get('currency_symbol','$')); ?><?php echo e(number_format($order->net_total, 2)); ?></p>
                            <?php if($order->status === 'refunded'): ?>
                                <p class="text-xxs text-red-500">-<?php echo e(\App\Models\Setting::get('currency_symbol','$')); ?><?php echo e(number_format($order->refund_amount, 2)); ?> refunded</p>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3 text-gray-400"><?php echo e($order->created_at->format('d M, h:i A')); ?></td>
                        <td class="px-5 py-3 text-right space-x-3 whitespace-nowrap">
                            <?php if($order->status === 'completed'): ?>
                                <a href="<?php echo e(route('orders.receipt', $order)); ?>" target="_blank" class="text-brand-600 hover:underline text-xs font-medium">Receipt</a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('refund', $order)): ?>
                                    <button type="button" data-refund-order="<?php echo e($order->id); ?>" data-total="<?php echo e($order->total); ?>"
                                            class="text-red-500 hover:underline text-xs font-medium">Refund</button>
                                <?php endif; ?>
                            <?php elseif($order->status === 'open'): ?>
                                <a href="<?php echo e(route('orders.pos', ['table' => $order->dining_table_id])); ?>" class="text-brand-600 hover:underline text-xs font-medium">Continue</a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cancel', $order)): ?>
                                    <button type="button" data-cancel-order="<?php echo e($order->id); ?>" class="text-red-500 hover:underline text-xs font-medium">Cancel</button>
                                <?php endif; ?>
                            <?php elseif($order->status === 'refunded'): ?>
                                <a href="<?php echo e(route('orders.receipt', $order)); ?>" target="_blank" class="text-brand-600 hover:underline text-xs font-medium">Receipt</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4"><?php echo e($orders->links()); ?></div>
</div>

<?php echo $__env->make('orders.partials._cancel_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('orders.partials._refund_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/orders/history.blade.php ENDPATH**/ ?>