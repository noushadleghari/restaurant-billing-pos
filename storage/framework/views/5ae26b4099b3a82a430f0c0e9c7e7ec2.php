<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> · <?php echo e(\App\Services\SettingService::class ? config('app.name') : 'Cafe POS'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    

    <?php echo $__env->yieldPushContent('head'); ?>
</head>

<body class="h-full">
    <div class="min-h-screen flex">
        
<aside class="hidden lg:flex w-[220px] h-screen shrink-0 flex-col bg-white border-r border-gray-200">            <div class="h-16 flex items-center gap-2 px-5 border-b border-gray-100">
                <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white font-bold">☕
                </div>
                <span
                    class="font-bold text-gray-800 truncate"><?php echo e(\App\Models\Setting::get('business_name', 'Cafe POS')); ?></span>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="<?php echo e(route('dashboard')); ?>"
                    class="sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <span>🏠</span> Dashboard
                </a>
                <a href="<?php echo e(route('tables.index')); ?>"
                    class="sidebar-link <?php echo e(request()->routeIs('tables.index') ? 'active' : ''); ?>">
                    <span>🍽️</span> Tables
                </a>
                <a href="<?php echo e(route('orders.pos')); ?>"
                    class="sidebar-link <?php echo e(request()->routeIs('orders.pos') ? 'active' : ''); ?>">
                    <span>🧾</span> Billing
                </a>
                <a href="<?php echo e(route('orders.history')); ?>"
                    class="sidebar-link <?php echo e(request()->routeIs('orders.history') ? 'active' : ''); ?>">
                    <span>📜</span> Order History
                </a>
                <a href="<?php echo e(route('products.index')); ?>"
                    class="sidebar-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">
                    <span>📦</span> Products
                </a>
                <a href="<?php echo e(route('customers.index')); ?>"
                    class="sidebar-link <?php echo e(request()->routeIs('customers.*') ? 'active' : ''); ?>">
                    <span>👤</span> Customers
                </a>
                <a href="<?php echo e(route('tables.manage')); ?>"
                    class="sidebar-link <?php echo e(request()->routeIs('tables.manage') ? 'active' : ''); ?>">
                    <span>🪑</span> Manage Tables
                </a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reports')): ?>
                    <a href="<?php echo e(route('reports.index')); ?>"
                        class="sidebar-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                        <span>📊</span> Sales Reports
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users')): ?>
                    <a href="<?php echo e(route('users.index')); ?>"
                        class="sidebar-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                        <span>👥</span> User Management
                    </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny',App\Models\Setting::class)): ?>
                    <a href="<?php echo e(route('settings.index')); ?>"
                        class="sidebar-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                        <span>⚙️</span> Settings
                    <?php endif; ?>
                </a>
            </nav>
            <div class="p-3 border-t border-gray-100">
                <div class="flex items-center gap-3 px-2 py-2">
                    <div
                        class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm">
                        <?php echo e(strtoupper(substr(auth()->user()->name ?? 'U', 0, 1))); ?>

                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-xs text-gray-400 capitalize"><?php echo e(auth()->user()->role); ?></p>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-1">
                    <?php echo csrf_field(); ?>
                    <button class="w-full text-left sidebar-link text-red-500 hover:bg-red-50 hover:text-red-600">
                        <span>🚪</span> Logout
                    </button>
                </form>
            </div>
        </aside>

        
        <div
            class="lg:hidden fixed top-0 inset-x-0 h-14 bg-white border-b border-gray-200 flex items-center justify-between px-4 z-40">
            <button onclick="document.getElementById('mobile-nav').classList.toggle('hidden')"
                class="text-2xl">☰</button>
            <span class="font-bold text-gray-800"><?php echo e(\App\Models\Setting::get('business_name', 'Cafe POS')); ?></span>
            <a href="<?php echo e(route('orders.pos')); ?>" class="text-xl">🧾</a>
        </div>
        <div id="mobile-nav"
            class="hidden lg:hidden fixed top-14 inset-x-0 bg-white border-b border-gray-200 z-40 p-3 space-y-1">
            <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-link">🏠 Dashboard</a>
            <a href="<?php echo e(route('tables.index')); ?>" class="sidebar-link">🍽️ Tables</a>
            <a href="<?php echo e(route('orders.pos')); ?>" class="sidebar-link">🧾 Billing</a>
            <a href="<?php echo e(route('orders.history')); ?>" class="sidebar-link">📜 Order History</a>
            <a href="<?php echo e(route('products.index')); ?>" class="sidebar-link">📦 Products</a>
            <a href="<?php echo e(route('customers.index')); ?>" class="sidebar-link">👤 Customers</a>
            <a href="<?php echo e(route('tables.manage')); ?>" class="sidebar-link">🪑 Manage Tables</a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-reports')): ?>
                <a href="<?php echo e(route('reports.index')); ?>" class="sidebar-link">📊 Sales Reports</a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-users')): ?>
                <a href="<?php echo e(route('users.index')); ?>" class="sidebar-link">
                    👥 User Management
                </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny',App\Models\Setting::class)): ?>
                <a href="<?php echo e(route('settings.index')); ?>" class="sidebar-link">⚙️ Settings</a>
            <?php endif; ?>
        </div>

        
<main class="flex-1 min-w-0 h-screen overflow-y-auto pt-14 lg:pt-0">            <?php if(session('success')): ?>
                <div data-flash
                    class="fixed top-5 right-5 z-[100] bg-brand-600 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-lg flex items-center gap-3">
                    <span>✅ <?php echo e(session('success')); ?></span>
                    <button data-flash-close class="opacity-80 hover:opacity-100">✕</button>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div data-flash
                    class="fixed top-5 right-5 z-[100] bg-red-600 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-lg flex items-center gap-3">
                    <span>⚠️ <?php echo e(session('error')); ?></span>
                    <button data-flash-close class="opacity-80 hover:opacity-100">✕</button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>

</html>
<?php /**PATH /home/noshad/Desktop/Laravel-projects/pos-system/resources/views/layouts/app.blade.php ENDPATH**/ ?>