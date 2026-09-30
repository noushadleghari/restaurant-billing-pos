<?php $__env->startSection('title', 'Categories'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="<?php echo e(route('products.index')); ?>" class="text-sm text-gray-400 hover:text-gray-600">← Back to Products</a>
            <h1 class="text-2xl font-bold text-gray-800 mt-1">Categories</h1>
        </div>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Category</th>
                    <th class="text-left px-5 py-3">Products</th>
                    <th class="text-right px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-5 py-3">
                            <span class="inline-block w-3 h-3 rounded-full mr-2" style="background:<?php echo e($cat->color); ?>"></span>
                            <?php echo e($cat->name); ?>

                        </td>
                        <td class="px-5 py-3 text-gray-500"><?php echo e($cat->products_count); ?></td>
                        <td class="px-5 py-3 text-right">
                            <form method="POST" action="<?php echo e(route('categories.destroy', $cat)); ?>" class="inline"
                                  onsubmit="return confirm('Delete this category?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="text-red-500 hover:underline text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4"><?php echo e($categories->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/products/categories.blade.php ENDPATH**/ ?>