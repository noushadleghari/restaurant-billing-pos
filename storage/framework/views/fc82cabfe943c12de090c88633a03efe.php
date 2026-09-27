<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Name</th>
                <th class="text-left px-5 py-3">Phone</th>
                <th class="text-left px-5 py-3">Orders</th>
                <th class="text-right px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-5 py-3 font-medium text-gray-800"><?php echo e($customer->name); ?></td>
                    <td class="px-5 py-3 text-gray-500"><?php echo e($customer->phone); ?></td>
                    <td class="px-5 py-3 text-gray-500"><?php echo e($customer->orders_count); ?></td>
                    <td class="px-5 py-3 text-right space-x-3">
                        <a href="<?php echo e(route('customers.edit', $customer)); ?>" class="text-brand-600 hover:underline text-xs font-medium">Edit</a>
                        <form method="POST" action="<?php echo e(route('customers.destroy', $customer)); ?>" class="inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="button" data-delete-customer class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">No customers found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="mt-4"><?php echo e($customers->links()); ?></div>
<?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/customers/partials/_table.blade.php ENDPATH**/ ?>