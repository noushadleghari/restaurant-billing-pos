@extends('layouts.app')
@section('title', 'Customers')

@section('content')
<div class="p-4 lg:p-8 max-w-6xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Customers</h1>
            <p class="text-gray-400 text-sm">Keep track of your regulars</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn-primary">+ Add Customer</a>
    </div>

    <input type="text" id="customer-search" placeholder="🔍 Search by name or phone..." class="input max-w-xs mb-5">

    <div id="customer-table-wrapper">
        @include('customers.partials._table', ['customers' => $customers])
    </div>
</div>
@endsection
