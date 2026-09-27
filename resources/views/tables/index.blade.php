@extends('layouts.app')
@section('title', 'Tables')

@section('content')
<div class="p-4 lg:p-8 max-w-6xl mx-auto">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tables</h1>
            <p class="text-gray-400 text-sm">Tap a table to start or continue billing</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('orders.pos') }}" class="btn-secondary">🧾 Takeaway / Manual Bill</a>
            <a href="{{ route('tables.manage') }}" class="btn-secondary">🪑 Manage Tables</a>
        </div>
    </div>

    <div class="flex items-center gap-4 mb-5">
        <input type="text" id="table-search" placeholder="🔍 Search table..." class="input max-w-xs">
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1">🟢 Available</span>
            <span class="flex items-center gap-1">🔴 Occupied</span>
        </div>
    </div>

    <div id="table-grid-wrapper">
        @include('tables.partials._grid', ['tables' => $tables])
    </div>
</div>
@endsection
