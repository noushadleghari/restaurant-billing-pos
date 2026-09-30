@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="p-4 lg:p-8 max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Users
            </h1>

            <p class="text-gray-400 text-sm">
                Manage administrators and cashiers
            </p>
        </div>

        <a href="{{ route('users.create') }}" class="btn-primary">
            + Add User
        </a>
    </div>

    <div id="user-table-wrapper">
        @include('users.partials._table', ['users' => $users])
    </div>

</div>
@endsection