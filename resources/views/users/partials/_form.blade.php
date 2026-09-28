<div>
    <label for="name" class="label">Name</label>

    <input type="text" name="name" id="name" class="input" value="{{ old('name', $user->name ?? '') }}" placeholder="Enter user name">

    @error('name')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="email" class="label">Email<sup style="color: red">*</sup></label>

    <input type="email" name="email" id="email" class="input"
        value="{{ old('email', $user->email ?? '') }}"
        placeholder="user@example.com">

    @error('email')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="role" class="label">Role</label>

    <select name="role" id="role" class="input">
        <option value="cashier"
            @selected(old('role', $user->role ?? 'cashier') === 'cashier')>
            Cashier
        </option>

        <option value="admin"
            @selected(old('role', $user->role ?? '') === 'admin')>
            Admin
        </option>
    </select>

    @error('role')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="password" class="label">
        Password

        @isset($user)
            <span class="text-gray-400 font-normal">
                (leave blank to keep current password)
            </span>
        @endisset
    </label>

    <input
        type="password"
        name="password"
        id="password"
        class="input"
        placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Minimum 8 characters' }}"
    >

    @error('password')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="password_confirmation" class="label">
        Confirm Password
    </label>

    <input
        type="password"
        name="password_confirmation"
        id="password_confirmation"
        class="input"
        placeholder="Confirm password"
    >
</div>

<div class="flex items-center gap-3">
    <input
        type="checkbox"
        name="is_active"
        id="is_active"
        value="1"
        class="rounded border-gray-300 text-brand-600 focus:ring-brand-500"
        @checked(old('is_active', $user->is_active ?? true))
    >

    <label for="is_active" class="text-sm text-gray-700">
        Active account
    </label>
</div>

<div class="pt-2">
    <button type="submit" class="btn-primary w-full">
        {{ isset($user) ? 'Update User' : 'Create User' }}
    </button>
</div>