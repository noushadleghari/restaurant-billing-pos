<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-8 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Settings</h1>
    <p class="text-gray-400 text-sm mb-6">Business info shown on receipts and used across the system</p>

    <form id="settings-form" method="POST" action="<?php echo e(route('settings.update')); ?>" enctype="multipart/form-data" class="card p-5 space-y-4" novalidate>
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <div>
            <label class="label">Logo <span class="text-gray-400 font-normal">(optional)</span></label>
            <div class="flex items-center gap-4">
                <img id="logo-preview" src="<?php echo e(!empty($settings['logo']) ? \Illuminate\Support\Facades\Storage::disk('public')->url($settings['logo']) : ''); ?>"
                     class="w-16 h-16 rounded-xl object-contain bg-gray-50 border border-gray-200 <?php echo e(empty($settings['logo']) ? 'hidden' : ''); ?>">
                <label class="btn-secondary cursor-pointer">
                    📷 Upload Logo
                    <input type="file" id="logo-input" name="logo" accept="image/*" class="hidden">
                </label>
            </div>
        </div>

        <div data-field>
            <label class="label">Business Name *</label>
            <input type="text" name="business_name" value="<?php echo e(old('business_name', $settings['business_name'])); ?>"
                   class="input <?php $__errorArgs = ['business_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="required|max:100">
            <p data-error class="error-text <?php echo e($errors->has('business_name') ? '' : 'hidden'); ?>"><?php echo e($errors->first('business_name')); ?></p>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div data-field>
                <label class="label">Phone</label>
                <input type="text" name="phone" value="<?php echo e(old('phone', $settings['phone'])); ?>" class="input" data-validate="max:30">
            </div>
            <div data-field>
                <label class="label">Currency Symbol *</label>
                <input type="text" name="currency_symbol" value="<?php echo e(old('currency_symbol', $settings['currency_symbol'])); ?>"
                       class="input <?php $__errorArgs = ['currency_symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="required|max:5">
                <p data-error class="error-text <?php echo e($errors->has('currency_symbol') ? '' : 'hidden'); ?>"><?php echo e($errors->first('currency_symbol')); ?></p>
            </div>
        </div>

        <div data-field>
            <label class="label">Address</label>
            <textarea name="address" rows="2" class="input" data-validate="max:255"><?php echo e(old('address', $settings['address'])); ?></textarea>
        </div>

        <div data-field>
            <label class="label">Default Tax % *</label>
            <input type="text" name="tax_percent" value="<?php echo e(old('tax_percent', $settings['tax_percent'])); ?>"
                   class="input <?php $__errorArgs = ['tax_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="required|number" data-numeric-only="decimal" inputmode="decimal">
            <p data-error class="error-text <?php echo e($errors->has('tax_percent') ? '' : 'hidden'); ?>"><?php echo e($errors->first('tax_percent')); ?></p>
        </div>

        <div data-field>
            <label class="label">Receipt Footer Note</label>
            <input type="text" name="receipt_footer" value="<?php echo e(old('receipt_footer', $settings['receipt_footer'])); ?>" class="input" data-validate="max:255" placeholder="Thank you for visiting!">
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary btn-lg">Save Settings</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/settings/index.blade.php ENDPATH**/ ?>