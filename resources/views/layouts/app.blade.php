<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ \App\Services\SettingService::class ? config('app.name') : 'Cafe POS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Chart.js only needed on Reports; cheap CDN include, guarded by allowed script hosts --}}
    
    @stack('head')
</head>
<body class="h-full">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="w-55 shrink-0 bg-white border-r border-gray-200 flex-col hidden lg:flex">
            <div class="h-16 flex items-center gap-2 px-5 border-b border-gray-100">
                <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center text-white font-bold">☕</div>
                <span class="font-bold text-gray-800 truncate">{{ \App\Models\Setting::get('business_name', 'Cafe POS') }}</span>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span>🏠</span> Dashboard
                </a>
                <a href="{{ route('tables.index') }}" class="sidebar-link {{ request()->routeIs('tables.index') ? 'active' : '' }}">
                    <span>🍽️</span> Tables
                </a>
                <a href="{{ route('orders.pos') }}" class="sidebar-link {{ request()->routeIs('orders.pos') ? 'active' : '' }}">
                    <span>🧾</span> Billing
                </a>
                <a href="{{ route('orders.history') }}" class="sidebar-link {{ request()->routeIs('orders.history') ? 'active' : '' }}">
                    <span>📜</span> Order History
                </a>
                <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <span>📦</span> Products
                </a>
                <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <span>👤</span> Customers
                </a>
                <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <span>📊</span> Sales Reports
                </a>
                <a href="{{ route('tables.manage') }}" class="sidebar-link {{ request()->routeIs('tables.manage') ? 'active' : '' }}">
                    <span>🪑</span> Manage Tables
                </a>
                <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <span>⚙️</span> Settings
                </a>
            </nav>
            <div class="p-3 border-t border-gray-100">
                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button class="w-full text-left sidebar-link text-red-500 hover:bg-red-50 hover:text-red-600">
                        <span>🚪</span> Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile top bar --}}
        <div class="lg:hidden fixed top-0 inset-x-0 h-14 bg-white border-b border-gray-200 flex items-center justify-between px-4 z-40">
            <button onclick="document.getElementById('mobile-nav').classList.toggle('hidden')" class="text-2xl">☰</button>
            <span class="font-bold text-gray-800">{{ \App\Models\Setting::get('business_name', 'Cafe POS') }}</span>
            <a href="{{ route('orders.pos') }}" class="text-xl">🧾</a>
        </div>
        <div id="mobile-nav" class="hidden lg:hidden fixed top-14 inset-x-0 bg-white border-b border-gray-200 z-40 p-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="sidebar-link">🏠 Dashboard</a>
            <a href="{{ route('tables.index') }}" class="sidebar-link">🍽️ Tables</a>
            <a href="{{ route('orders.pos') }}" class="sidebar-link">🧾 Billing</a>
            <a href="{{ route('orders.history') }}" class="sidebar-link">📜 Order History</a>
            <a href="{{ route('products.index') }}" class="sidebar-link">📦 Products</a>
            <a href="{{ route('customers.index') }}" class="sidebar-link">👤 Customers</a>
            <a href="{{ route('reports.index') }}" class="sidebar-link">📊 Sales Reports</a>
            <a href="{{ route('tables.manage') }}" class="sidebar-link">🪑 Manage Tables</a>
            <a href="{{ route('settings.index') }}" class="sidebar-link">⚙️ Settings</a>
        </div>

        {{-- Main content --}}
        <main class="flex-1 min-w-0 pt-14 lg:pt-0">
            @if (session('success'))
                <div data-flash class="fixed top-5 right-5 z-[100] bg-brand-600 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-lg flex items-center gap-3">
                    <span>✅ {{ session('success') }}</span>
                    <button data-flash-close class="opacity-80 hover:opacity-100">✕</button>
                </div>
            @endif
            @if (session('error'))
                <div data-flash class="fixed top-5 right-5 z-[100] bg-red-600 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-lg flex items-center gap-3">
                    <span>⚠️ {{ session('error') }}</span>
                    <button data-flash-close class="opacity-80 hover:opacity-100">✕</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
