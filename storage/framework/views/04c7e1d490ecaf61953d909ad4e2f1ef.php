<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login · <?php echo e(\App\Models\Setting::get('business_name', 'Cafe POS')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gradient-to-br from-brand-50 via-white to-brand-50 min-h-screen flex items-center justify-center p-4">

    <?php if(session('success')): ?>
        <div data-flash class="fixed top-5 right-5 z-[100] bg-brand-600 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-lg">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg shadow-brand-200">☕</div>
            <h1 class="text-xl font-bold text-gray-800"><?php echo e(\App\Models\Setting::get('business_name', 'Cafe POS')); ?></h1>
            <p class="text-sm text-gray-400">Sign in to start billing</p>
        </div>

        <div class="card p-6">
            <form id="login-form" method="POST" action="<?php echo e(route('login.store')); ?>" novalidate>
                <?php echo csrf_field(); ?>

                <div class="mb-4" data-field>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>"
                           class="input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           data-validate="required|email" placeholder="you@cafe.com" autofocus>
                    <p data-error class="error-text <?php echo e($errors->has('email') ? '' : 'hidden'); ?>"><?php echo e($errors->first('email')); ?></p>
                </div>

                <div class="mb-2" data-field>
                    <label class="label">Password</label>
                    <input type="password" name="password"
                           class="input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           data-validate="required" placeholder="••••••••">
                    <p data-error class="error-text <?php echo e($errors->has('password') ? '' : 'hidden'); ?>"><?php echo e($errors->first('password')); ?></p>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-500 mb-5 mt-3">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    Keep me signed in
                </label>

                <button type="submit" class="btn-primary btn-lg w-full">Sign In</button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            Demo: admin@cafepos.test / password
        </p>
    </div>
</body>
</html>
<?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/auth/login.blade.php ENDPATH**/ ?>