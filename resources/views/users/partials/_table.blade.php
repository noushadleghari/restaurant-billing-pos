<div class="card overflow-hidden">

    <table class="w-full text-sm">

        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-3">Name</th>
                <th class="text-left px-5 py-3">Email</th>
                <th class="text-left px-5 py-3">Role</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">

            @forelse($users as $user)

                <tr>

                    <td class="px-5 py-3 font-medium text-gray-800">
                        {{ $user->name }}

                        @if(auth()->id() === $user->id)
                            <span class="text-xs text-gray-400">
                                (You)
                            </span>
                        @endif
                    </td>

                    <td class="px-5 py-3 text-gray-500">
                        {{ $user->email }}
                    </td>

                    <td class="px-5 py-3">
                        @if($user->role === 'admin')
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                Admin
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                Cashier
                            </span>
                        @endif
                    </td>

                    <td class="px-5 py-3">

                        @if($user->is_active)

                            <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                Active
                            </span>

                        @else

                            <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-400">
                                <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                Inactive
                            </span>

                        @endif

                    </td>

                    <td class="px-5 py-3 text-right space-x-3">

                        <a
                            href="{{ route('users.edit', $user) }}"
                            class="text-brand-600 hover:underline text-xs font-medium"
                        >
                            Edit
                        </a>

                        @if(auth()->id() !== $user->id)

                            <form
                                method="POST"
                                action="{{ route('users.destroy', $user) }}"
                                class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this user?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-red-500 hover:underline text-xs font-medium"
                                >
                                    Delete
                                </button>
                            </form>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td
                        colspan="5"
                        class="px-5 py-10 text-center text-gray-400"
                    >
                        No users found.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

<div class="mt-4">
    {{ $users->links() }}
</div>