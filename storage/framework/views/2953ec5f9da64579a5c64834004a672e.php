<?php $__env->startSection('title', 'Customers'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-6xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Customers</h1>
            <p class="text-gray-400 text-sm">Keep track of your regulars</p>
        </div>
        <a href="<?php echo e(route('customers.create')); ?>" class="btn-primary">+ Add Customer</a>
    </div>

    <input type="text" id="customer-search" placeholder="🔍 Search by name or phone..." class="input max-w-xs mb-5">

    <div id="customer-table-wrapper">
        <?php echo $__env->make('customers.partials._table', ['customers' => $customers], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/customers/index.blade.php ENDPATH**/ ?>