<?php $__env->startSection('title', 'Tables'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-6xl mx-auto">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tables</h1>
            <p class="text-gray-400 text-sm">Tap a table to start or continue billing</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('orders.pos')); ?>" class="btn-secondary">🧾 Takeaway / Manual Bill</a>
            <a href="<?php echo e(route('tables.manage')); ?>" class="btn-secondary">🪑 Manage Tables</a>
        </div>
    </div>

    <div class="flex items-center gap-4 mb-5">
        <input type="text" id="table-search" placeholder="🔍 Search table..." class="input max-w-xs">
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1">🟢 Available</span>
            <span class="flex items-center gap-1">🔴 Occupied</span>
        </div>
    </div>

    <div id="table-grid-wrapper">
        <?php echo $__env->make('tables.partials._grid', ['tables' => $tables], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/tables/index.blade.php ENDPATH**/ ?>