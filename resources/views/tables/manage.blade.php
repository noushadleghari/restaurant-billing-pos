@extends('layouts.app')
@section('title', 'Manage Tables')

@section('content')
<div class="p-4 lg:p-8 max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('tables.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Back to Tables</a>
            <h1 class="text-2xl font-bold text-gray-800 mt-1">Manage Tables</h1>
        </div>
        <button id="add-table-btn" class="btn-primary">+ Add Table</button>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Table</th>
                    <th class="text-left px-5 py-3">Capacity</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-right px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($tables as $table)
                    <tr>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $table->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $table->capacity }} seats</td>
                        <td class="px-5 py-3">
                            <span class="{{ $table->status === 'occupied' ? 'badge-red' : 'badge-green' }}">{{ ucfirst($table->status) }}</span>
                        </td>
                        <td class="px-5 py-3 text-right space-x-3">
                            <button type="button" data-edit-table="{{ $table->id }}" data-name="{{ $table->name }}" data-capacity="{{ $table->capacity }}"
                                    class="text-brand-600 hover:underline text-xs font-medium">Edit</button>
                            <form method="POST" action="{{ route('tables.destroy', $table) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" data-delete-table class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tables->links() }}</div>
</div>

{{-- Add/Edit table modal --}}
<div id="table-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="table-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="card relative w-full max-w-sm p-6">
        <h3 id="table-modal-title" class="font-bold text-gray-800 mb-4">Add Table</h3>
        <form id="table-form" method="POST" action="{{ route('tables.store') }}" novalidate>
            @csrf
            <input type="hidden" name="_method" value="POST">

            <div class="mb-4" data-field>
                <label class="label">Table Name / Number *</label>
                <input type="text" id="table-name" name="name" class="input" data-validate="required|max:30" placeholder="e.g. T1, VIP-1">
                <p data-error class="error-text hidden"></p>
            </div>
            <div class="mb-5" data-field>
                <label class="label">Seating Capacity *</label>
                <input type="text" id="table-capacity" name="capacity" class="input" data-validate="required|integer" data-numeric-only placeholder="4" inputmode="numeric">
                <p data-error class="error-text hidden"></p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">Save</button>
                <button type="button" id="close-table-modal" class="btn-secondary flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Reset modal to "Add" state whenever opened fresh (not via edit button)
    document.getElementById('add-table-btn').addEventListener('click', function () {
        document.getElementById('table-form').setAttribute('action', @json(route('tables.store')));
        document.getElementById('table-form').querySelector('[name=_method]').value = 'POST';
        document.getElementById('table-modal-title').textContent = 'Add Table';
        document.getElementById('table-name').value = '';
        document.getElementById('table-capacity').value = '';
    });
</script>
@endsection
