<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card overflow-hidden <?php echo e($product->is_available ? '' : 'unavailable'); ?>" data-product-card>
            <img src="<?php echo e($product->image_url); ?>" class="w-full h-32 object-cover bg-gray-100" loading="lazy" alt="<?php echo e($product->name); ?>">
            <div class="p-3">
                <p class="text-xs text-brand-600 font-semibold uppercase tracking-wide"><?php echo e($product->category->name); ?></p>
                <p class="font-semibold text-gray-800 truncate"><?php echo e($product->name); ?></p>
                <p class="text-brand-700 font-bold"><?php echo e(\App\Models\Setting::get('currency_symbol', '$')); ?><?php echo e(number_format($product->price, 2)); ?></p>

                <div class="flex items-center gap-1.5 mt-3">
                    <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn-secondary flex-1 !py-1.5 !text-xs">Edit</a>
                    <button type="button" data-toggle-availability="<?php echo e($product->id); ?>"
                            class="btn-secondary !py-1.5 !text-xs <?php echo e($product->is_available ? '' : 'bg-red-50 text-red-600 border-red-200'); ?>">
                        <?php echo e($product->is_available ? 'Available' : 'Out of stock'); ?>

                    </button>
                    <form method="POST" action="<?php echo e(route('products.destroy', $product)); ?>" class="inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="button" data-delete-product class="btn-secondary !py-1.5 !px-2 !text-xs text-red-500">🗑</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full text-center py-16 text-gray-400">
            <p class="text-4xl mb-2">📦</p>
            <p>No products found. <a href="<?php echo e(route('products.create')); ?>" class="text-brand-600 font-medium">Add your first product</a>.</p>
        </div>
    <?php endif; ?>
</div>

<div class="mt-6"><?php echo e($products->links()); ?></div>
<?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/products/partials/_grid.blade.php ENDPATH**/ ?>