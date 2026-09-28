<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Good day 👋</h1>
            <p class="text-gray-400 text-sm">Here's how things look today, <?php echo e(now()->format('D, M j')); ?></p>
        </div>
        <a href="<?php echo e(route('orders.pos')); ?>" class="btn-primary btn-lg">🧾 New Bill</a>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Today's Sales</p>
            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(\App\Models\Setting::get('currency_symbol', '$')); ?><?php echo e(number_format($todaySummary['total_sales'], 2)); ?></p>
            <p class="text-xs mt-1 <?php echo e($todaySummary['total_sales'] >= $yesterdaySummary['total_sales'] ? 'text-green-600' : 'text-red-500'); ?>">
                vs yesterday <?php echo e(\App\Models\Setting::get('currency_symbol', '$')); ?><?php echo e(number_format($yesterdaySummary['total_sales'], 2)); ?>

            </p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Orders Today</p>
            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($todaySummary['total_orders']); ?></p>
            <p class="text-xs mt-1 text-gray-400">Avg <?php echo e(\App\Models\Setting::get('currency_symbol', '$')); ?><?php echo e(number_format($todaySummary['avg_order_value'], 2)); ?>/order</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Tables Occupied</p>
            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($occupiedTables); ?> / <?php echo e($totalTables); ?></p>
            <p class="text-xs mt-1 text-gray-400"><?php echo e($openOrders); ?> open orders</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Out of Stock</p>
            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($lowAvailability); ?></p>
            <p class="text-xs mt-1 text-gray-400">products marked unavailable</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 card p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Recent Completed Orders</h2>
                <a href="<?php echo e(route('orders.history')); ?>" class="text-sm text-brand-600 font-medium hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                                <?php echo e($order->order_number); ?>

                                <?php if($order->status === 'refunded'): ?>
                                    <span class="badge-red !py-0.5">Refunded</span>
                                <?php endif; ?>
                            </p>
                            <p class="text-xs text-gray-400">
                                <?php echo e($order->diningTable?->name ?? 'Takeaway'); ?> · <?php echo e($order->completed_at?->diffForHumans()); ?>

                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-800"><?php echo e(\App\Models\Setting::get('currency_symbol', '$')); ?><?php echo e(number_format($order->net_total, 2)); ?></p>
                            <?php if($order->status === 'refunded'): ?>
                                <p class="text-xxs text-red-500">-<?php echo e(\App\Models\Setting::get('currency_symbol', '$')); ?><?php echo e(number_format($order->refund_amount, 2)); ?> refunded</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-400 py-8 text-center">No completed orders yet today.</p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Top Sellers Today</h2>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 truncate"><?php echo e($p->product_name); ?></span>
                        <span class="font-semibold text-gray-800">×<?php echo e($p->total_qty); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-400 text-center py-6">No sales yet today.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/dashboard/index.blade.php ENDPATH**/ ?>