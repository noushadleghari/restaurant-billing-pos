<?php $__env->startSection('title', 'Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Users
            </h1>

            <p class="text-gray-400 text-sm">
                Manage administrators and cashiers
            </p>
        </div>

        <a href="<?php echo e(route('users.create')); ?>" class="btn-primary">
            + Add User
        </a>
    </div>

    <div id="user-table-wrapper">
        <?php echo $__env->make('users.partials._table', ['users' => $users], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/users/index.blade.php ENDPATH**/ ?>