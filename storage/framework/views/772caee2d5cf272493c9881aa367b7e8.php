<?php $__env->startSection('title', 'Manage Tables'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-6xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="<?php echo e(route('tables.index')); ?>" class="text-sm text-gray-400 hover:text-gray-600">← Back to Tables</a>
            <h1 class="text-2xl font-bold text-gray-800 mt-1">Manage Tables</h1>
        </div>
        <button id="add-table-btn" class="btn-primary">+ Add Table</button>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Table</th>
                    <th class="text-left px-5 py-3">Capacity</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-right px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800"><?php echo e($table->name); ?></td>
                        <td class="px-5 py-3 text-gray-500"><?php echo e($table->capacity); ?> seats</td>
                        <td class="px-5 py-3">
                            <span class="<?php echo e($table->status === 'occupied' ? 'badge-red' : 'badge-green'); ?>"><?php echo e(ucfirst($table->status)); ?></span>
                        </td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <button type="button" data-edit-table="<?php echo e($table->id); ?>" data-name="<?php echo e($table->name); ?>" data-capacity="<?php echo e($table->capacity); ?>"
                                    class="text-brand-600 hover:underline text-xs font-medium">Edit</button>
                            <form method="POST" action="<?php echo e(route('tables.destroy', $table)); ?>" class="inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="button" data-delete-table class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4"><?php echo e($tables->links()); ?></div>
</div>


<div id="table-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="table-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="card relative w-full max-w-sm p-6">
        <h3 id="table-modal-title" class="font-bold text-gray-800 mb-4">Add Table</h3>
        <form id="table-form" method="POST" action="<?php echo e(route('tables.store')); ?>" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="POST">

            <div class="mb-4" data-field>
                <label class="label">Table Name / Number *</label>
                <input type="text" id="table-name" name="name" class="input" data-validate="required|max:30" placeholder="e.g. T1, VIP-1">
                <p data-error class="error-text hidden"></p>
            </div>
            <div class="mb-5" data-field>
                <label class="label">Seating Capacity *</label>
                <input type="text" id="table-capacity" name="capacity" class="input" data-validate="required|integer" data-numeric-only placeholder="4" inputmode="numeric">
                <p data-error class="error-text hidden"></p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">Save</button>
                <button type="button" id="close-table-modal" class="btn-secondary flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.routes = {
        tablesStore: <?php echo json_encode(route('tables.store'), 15, 512) ?>,
    };
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/tables/manage.blade.php ENDPATH**/ ?>