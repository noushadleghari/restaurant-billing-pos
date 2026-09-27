<div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-4">
    @forelse($tables as $table)
        @php
            $itemCount = $table->activeOrder?->items->sum('quantity') ?? 0;
        @endphp
        <div class="table-tile {{ $table->status }}" data-table-tile="{{ $table->id }}">
            <span class="text-2xl">{{ $table->status === 'occupied' ? '🔴' : '🟢' }}</span>
            <span class="font-bold text-lg">{{ $table->name }}</span>
            <span class="text-xxs opacity-70">{{ $table->capacity }} seats</span>
            @if($table->status === 'occupied' && $itemCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow">
                    {{ $itemCount }}
                </span>
            @endif
        </div>
    @empty
        <div class="col-span-full text-center text-gray-400 py-16">
            No tables found. <a href="{{ route('tables.manage') }}" class="text-brand-600 font-medium">Add tables</a> to get started.
        </div>
    @endforelse
</div>
