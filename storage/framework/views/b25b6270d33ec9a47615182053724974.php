<div>
    <label for="name" class="label">Name</label>

    <input
        type="text"
        name="name"
        id="name"
        class="input"
        value="<?php echo e(old('name', $user->name ?? '')); ?>"
        placeholder="Enter user name"
    >

    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label for="email" class="label">Email<sup style="color: red">*</sup></label>

    <input
        type="email"
        name="email"
        id="email"
        class="input"
        value="<?php echo e(old('email', $user->email ?? '')); ?>"
        placeholder="user@example.com"
    >

    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label for="role" class="label">Role</label>

    <select name="role" id="role" class="input">
        <option value="cashier"
            <?php if(old('role', $user->role ?? 'cashier') === 'cashier'): echo 'selected'; endif; ?>>
            Cashier
        </option>

        <option value="admin"
            <?php if(old('role', $user->role ?? '') === 'admin'): echo 'selected'; endif; ?>>
            Admin
        </option>
    </select>

    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label for="password" class="label">
        Password

        <?php if(isset($user)): ?>
            <span class="text-gray-400 font-normal">
                (leave blank to keep current password)
            </span>
        <?php endif; ?>
    </label>

    <input
        type="password"
        name="password"
        id="password"
        class="input"
        placeholder="<?php echo e(isset($user) ? 'Leave blank to keep current password' : 'Minimum 8 characters'); ?>"
    >

    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label for="password_confirmation" class="label">
        Confirm Password
    </label>

    <input
        type="password"
        name="password_confirmation"
        id="password_confirmation"
        class="input"
        placeholder="Confirm password"
    >
</div>

<div class="flex items-center gap-3">
    <input
        type="checkbox"
        name="is_active"
        id="is_active"
        value="1"
        class="rounded border-gray-300 text-brand-600 focus:ring-brand-500"
        <?php if(old('is_active', $user->is_active ?? true)): echo 'checked'; endif; ?>
    >

    <label for="is_active" class="text-sm text-gray-700">
        Active account
    </label>
</div>

<div class="pt-2">
    <button type="submit" class="btn-primary w-full">
        <?php echo e(isset($user) ? 'Update User' : 'Create User'); ?>

    </button>
</div><?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/users/partials/_form.blade.php ENDPATH**/ ?>