<div data-field>
    <label class="label">Full Name *</label>
    <input type="text" name="name" value="<?php echo e(old('name', $customer->name ?? '')); ?>"
           class="input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="required|min:2|max:100" placeholder="John Doe">
    <p data-error class="error-text <?php echo e($errors->has('name') ? '' : 'hidden'); ?>"><?php echo e($errors->first('name')); ?></p>
</div>

<div data-field>
    <label class="label">Phone *</label>
    <input type="text" name="phone" value="<?php echo e(old('phone', $customer->phone ?? '')); ?>"
           class="input <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="required|phone" data-numeric-only="decimal" placeholder="0300 1234567">
    <p data-error class="error-text <?php echo e($errors->has('phone') ? '' : 'hidden'); ?>"><?php echo e($errors->first('phone')); ?></p>
</div>

<div data-field>
    <label class="label">Email <span class="text-gray-400 font-normal">(optional)</span></label>
    <input type="email" name="email" value="<?php echo e(old('email', $customer->email ?? '')); ?>"
           class="input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="email" placeholder="john@example.com">
    <p data-error class="error-text <?php echo e($errors->has('email') ? '' : 'hidden'); ?>"><?php echo e($errors->first('email')); ?></p>
</div>

<div data-field>
    <label class="label">Address <span class="text-gray-400 font-normal">(optional)</span></label>
    <textarea name="address" rows="2" class="input <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-validate="max:255"><?php echo e(old('address', $customer->address ?? '')); ?></textarea>
    <p data-error class="error-text <?php echo e($errors->has('address') ? '' : 'hidden'); ?>"><?php echo e($errors->first('address')); ?></p>
</div>

<div class="flex items-center gap-3 pt-2">
    <button type="submit" class="btn-primary btn-lg">Save Customer</button>
    <a href="<?php echo e(route('customers.index')); ?>" class="btn-secondary btn-lg">Cancel</a>
</div>
<?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/customers/partials/_form.blade.php ENDPATH**/ ?>