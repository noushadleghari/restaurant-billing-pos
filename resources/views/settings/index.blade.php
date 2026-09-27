@extends('layouts.app')
@section('title', 'Settings')

@section('content')
<div class="p-4 lg:p-8 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Settings</h1>
    <p class="text-gray-400 text-sm mb-6">Business info shown on receipts and used across the system</p>

    <form id="settings-form" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="card p-5 space-y-4" novalidate>
        @csrf @method('PUT')

        <div>
            <label class="label">Logo <span class="text-gray-400 font-normal">(optional)</span></label>
            <div class="flex items-center gap-4">
                <img id="logo-preview" src="{{ !empty($settings['logo']) ? \Illuminate\Support\Facades\Storage::disk('public')->url($settings['logo']) : '' }}"
                     class="w-16 h-16 rounded-xl object-contain bg-gray-50 border border-gray-200 {{ empty($settings['logo']) ? 'hidden' : '' }}">
                <label class="btn-secondary cursor-pointer">
                    📷 Upload Logo
                    <input type="file" id="logo-input" name="logo" accept="image/*" class="hidden">
                </label>
            </div>
        </div>

        <div data-field>
            <label class="label">Business Name *</label>
            <input type="text" name="business_name" value="{{ old('business_name', $settings['business_name']) }}"
                   class="input @error('business_name') input-error @enderror" data-validate="required|max:100">
            <p data-error class="error-text {{ $errors->has('business_name') ? '' : 'hidden' }}">{{ $errors->first('business_name') }}</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div data-field>
                <label class="label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $settings['phone']) }}" class="input" data-validate="max:30">
            </div>
            <div data-field>
                <label class="label">Currency Symbol *</label>
                <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}"
                       class="input @error('currency_symbol') input-error @enderror" data-validate="required|max:5">
                <p data-error class="error-text {{ $errors->has('currency_symbol') ? '' : 'hidden' }}">{{ $errors->first('currency_symbol') }}</p>
            </div>
        </div>

        <div data-field>
            <label class="label">Address</label>
            <textarea name="address" rows="2" class="input" data-validate="max:255">{{ old('address', $settings['address']) }}</textarea>
        </div>

        <div data-field>
            <label class="label">Default Tax % *</label>
            <input type="text" name="tax_percent" value="{{ old('tax_percent', $settings['tax_percent']) }}"
                   class="input @error('tax_percent') input-error @enderror" data-validate="required|number" data-numeric-only="decimal" inputmode="decimal">
            <p data-error class="error-text {{ $errors->has('tax_percent') ? '' : 'hidden' }}">{{ $errors->first('tax_percent') }}</p>
        </div>

        <div data-field>
            <label class="label">Receipt Footer Note</label>
            <input type="text" name="receipt_footer" value="{{ old('receipt_footer', $settings['receipt_footer']) }}" class="input" data-validate="max:255" placeholder="Thank you for visiting!">
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary btn-lg">Save Settings</button>
        </div>
    </form>
</div>
@endsection
