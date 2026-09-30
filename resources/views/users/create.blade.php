@extends('layouts.app')
@section('title', 'Add User')

@section('content')
<div class="p-4 lg:p-8 max-w-lg mx-auto">

    <a href="{{ route('users.index') }}"
       class="text-sm text-gray-400 hover:text-gray-600">
        ← Back to Users
    </a>

    <h1 class="text-2xl font-bold text-gray-800 mt-1 mb-6">
        Add User
    </h1>

    <form
        method="POST"
        action="{{ route('users.store') }}"
        class="card p-5 space-y-4"
    >
        @csrf

        @include('users.partials._form')
    </form>

</div>
@endsection