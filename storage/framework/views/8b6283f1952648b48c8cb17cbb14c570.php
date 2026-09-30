<?php $__env->startSection('title', 'Edit User'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-lg mx-auto">

    <a href="<?php echo e(route('users.index')); ?>"
       class="text-sm text-gray-400 hover:text-gray-600">
        ← Back to Users
    </a>

    <h1 class="text-2xl font-bold text-gray-800 mt-1 mb-6">
        Edit User
    </h1>

    <form
        method="POST"
        action="<?php echo e(route('users.update', $user)); ?>"
        class="card p-5 space-y-4"
    >
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <?php echo $__env->make('users.partials._form', ['user' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/users/edit.blade.php ENDPATH**/ ?>