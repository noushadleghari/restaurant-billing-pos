<?php $__env->startSection('title', 'Sales Reports'); ?>

<?php $__env->startPush('head'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-6xl mx-auto">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Sales Reports</h1>
            <p class="text-gray-400 text-sm">Monitor how your business is performing</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <select name="range" class="input !py-2 !text-sm" onchange="this.form.submit()">
                <option value="today" <?php if($range==='today'): echo 'selected'; endif; ?>>Today</option>
                <option value="yesterday" <?php if($range==='yesterday'): echo 'selected'; endif; ?>>Yesterday</option>
                <option value="week" <?php if($range==='week'): echo 'selected'; endif; ?>>This Week</option>
                <option value="month" <?php if($range==='month'): echo 'selected'; endif; ?>>This Month</option>
            </select>
        </form>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Total Sales</p>
            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(\App\Models\Setting::get('currency_symbol','$')); ?><?php echo e(number_format($summary['total_sales'], 2)); ?></p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Orders</p>
            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($summary['total_orders']); ?></p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Avg Order Value</p>
            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(\App\Models\Setting::get('currency_symbol','$')); ?><?php echo e(number_format($summary['avg_order_value'], 2)); ?></p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-400 font-medium">Total Discounts</p>
            <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(\App\Models\Setting::get('currency_symbol','$')); ?><?php echo e(number_format($summary['total_discount'], 2)); ?></p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <div class="card p-5 lg:col-span-2">
            <h2 class="font-semibold text-gray-800 mb-4">Sales Trend</h2>
            <canvas id="salesChart" height="120"
                data-labels='<?php echo $salesByDay->pluck("date")->toJson(); ?>'
                data-values='<?php echo $salesByDay->pluck("total")->toJson(); ?>'></canvas>
        </div>
        <div class="card p-5">
            <h2 class="font-semibold text-gray-800 mb-4">By Payment Method</h2>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $paymentBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 capitalize"><?php echo e($pm->payment_method ?? 'Unknown'); ?> (<?php echo e($pm->orders_count); ?>)</span>
                        <span class="font-semibold text-gray-800"><?php echo e(\App\Models\Setting::get('currency_symbol','$')); ?><?php echo e(number_format($pm->total, 2)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-400 text-center py-6">No data for this period.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card p-5">
        <h2 class="font-semibold text-gray-800 mb-4">Top Selling Products</h2>
        <table class="w-full text-sm">
            <thead class="text-gray-400 text-xs uppercase">
                <tr><th class="text-left py-2">Product</th><th class="text-left py-2">Qty Sold</th><th class="text-left py-2">Revenue</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="py-2 text-gray-700"><?php echo e($p->product_name); ?></td>
                        <td class="py-2 text-gray-500"><?php echo e($p->total_qty); ?></td>
                        <td class="py-2 font-semibold text-gray-800"><?php echo e(\App\Models\Setting::get('currency_symbol','$')); ?><?php echo e(number_format($p->total_revenue, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="py-8 text-center text-gray-400">No sales in this period.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/reports/index.blade.php ENDPATH**/ ?>