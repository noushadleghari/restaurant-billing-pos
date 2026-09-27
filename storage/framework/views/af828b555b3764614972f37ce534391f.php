<?php $__env->startSection('title', 'Add Customer'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-lg mx-auto">
    <a href="<?php echo e(route('customers.index')); ?>" class="text-sm text-gray-400 hover:text-gray-600">← Back to Customers</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-1 mb-6">Add Customer</h1>

    <form id="customer-form" method="POST" action="<?php echo e(route('customers.store')); ?>" class="card p-5 space-y-4" novalidate>
        <?php echo $__env->make('customers.partials._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/customers/create.blade.php ENDPATH**/ ?>