<div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-4">
    <?php $__empty_1 = true; $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $itemCount = $table->activeOrder?->items->sum('quantity') ?? 0;
        ?>
        <div class="table-tile <?php echo e($table->status); ?>" data-table-tile="<?php echo e($table->id); ?>">
            <span class="text-2xl"><?php echo e($table->status === 'occupied' ? '🔴' : '🟢'); ?></span>
            <span class="font-bold text-lg"><?php echo e($table->name); ?></span>
            <span class="text-xxs opacity-70"><?php echo e($table->capacity); ?> seats</span>
            <?php if($table->status === 'occupied' && $itemCount > 0): ?>
                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow">
                    <?php echo e($itemCount); ?>

                </span>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full text-center text-gray-400 py-16">
            No tables found. <a href="<?php echo e(route('tables.manage')); ?>" class="text-brand-600 font-medium">Add tables</a> to get started.
        </div>
    <?php endif; ?>
</div>
<?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/tables/partials/_grid.blade.php ENDPATH**/ ?>