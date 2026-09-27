@extends('layouts.app')
@section('title', 'Edit Customer')

@section('content')
<div class="p-4 lg:p-8 max-w-lg mx-auto">
    <a href="{{ route('customers.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Back to Customers</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-1 mb-6">Edit Customer</h1>

    <form id="customer-form" method="POST" action="{{ route('customers.update', $customer) }}" class="card p-5 space-y-4" novalidate>
        @csrf @method('PUT')
        @include('customers.partials._form', ['customer' => $customer])
    </form>
</div>
@endsection
