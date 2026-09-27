<?php $__env->startSection('title', 'Edit Product'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="<?php echo e(route('products.index')); ?>" class="text-sm text-gray-400 hover:text-gray-600">← Back to Products</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-1">Edit Product</h1>
    </div>

    <form id="product-form" data-method="PUT" method="POST" action="<?php echo e(route('products.update', $product)); ?>" enctype="multipart/form-data" novalidate>
        <?php echo $__env->make('products.partials._form', ['mode' => 'edit', 'product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
</div>

<script>
    window.routes = {
        categoriesStore: <?php echo json_encode(route('categories.store'), 15, 512) ?>,
        productsIndex: <?php echo json_encode(route('products.index'), 15, 512) ?>,
    };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/products/edit.blade.php ENDPATH**/ ?>