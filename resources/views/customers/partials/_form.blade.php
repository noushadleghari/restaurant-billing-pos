<div data-field>
    <label class="label">Full Name *</label>
    <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}"
           class="input @error('name') input-error @enderror" data-validate="required|min:2|max:100" placeholder="John Doe">
    <p data-error class="error-text {{ $errors->has('name') ? '' : 'hidden' }}">{{ $errors->first('name') }}</p>
</div>

<div data-field>
    <label class="label">Phone *</label>
    <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}"
           class="input @error('phone') input-error @enderror" data-validate="required|phone" data-numeric-only="decimal" placeholder="0300 1234567">
    <p data-error class="error-text {{ $errors->has('phone') ? '' : 'hidden' }}">{{ $errors->first('phone') }}</p>
</div>

<div data-field>
    <label class="label">Email <span class="text-gray-400 font-normal">(optional)</span></label>
    <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}"
           class="input @error('email') input-error @enderror" data-validate="email" placeholder="john@example.com">
    <p data-error class="error-text {{ $errors->has('email') ? '' : 'hidden' }}">{{ $errors->first('email') }}</p>
</div>

<div data-field>
    <label class="label">Address <span class="text-gray-400 font-normal">(optional)</span></label>
    <textarea name="address" rows="2" class="input @error('address') input-error @enderror" data-validate="max:255">{{ old('address', $customer->address ?? '') }}</textarea>
    <p data-error class="error-text {{ $errors->has('address') ? '' : 'hidden' }}">{{ $errors->first('address') }}</p>
</div>

<div class="flex items-center gap-3 pt-2">
    <button type="submit" class="btn-primary btn-lg">Save Customer</button>
    <a href="{{ route('customers.index') }}" class="btn-secondary btn-lg">Cancel</a>
</div>
