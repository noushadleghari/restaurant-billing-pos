<?php $__env->startSection('title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-7xl mx-auto">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Products</h1>
            <p class="text-gray-400 text-sm">Manage your menu items</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('categories.index')); ?>" class="btn-secondary">🏷️ Categories</a>
            <a href="<?php echo e(route('products.create')); ?>" class="btn-primary">+ Add Product</a>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3 mb-5">
        <input type="text" id="product-search" placeholder="🔍 Search products or SKU..." class="input max-w-xs">
        <select id="category-filter" class="input max-w-[180px]">
            <option value="">All categories</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div id="product-grid-wrapper">
        <?php echo $__env->make('products.partials._grid', ['products' => $products], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/products/index.blade.php ENDPATH**/ ?>