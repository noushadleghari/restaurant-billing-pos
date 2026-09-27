<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Name</th>
                <th class="text-left px-5 py-3">Phone</th>
                <th class="text-left px-5 py-3">Orders</th>
                <th class="text-right px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($customers as $customer)
                <tr>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $customer->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $customer->phone }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $customer->orders_count }}</td>
                    <td class="px-5 py-3 text-right space-x-3">
                        <a href="{{ route('customers.edit', $customer) }}" class="text-brand-600 hover:underline text-xs font-medium">Edit</a>
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="button" data-delete-customer class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">No customers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $customers->links() }}</div>
