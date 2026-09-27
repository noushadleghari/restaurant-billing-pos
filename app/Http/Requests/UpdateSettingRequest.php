<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'currency_symbol' => ['required', 'string', 'max:5'],
            'tax_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'receipt_footer' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'business_name.required' => 'Please enter your business name.',
            'currency_symbol.required' => 'Please enter a currency symbol (e.g. $, Rs, ₹).',
            'tax_percent.required' => 'Please enter a default tax percentage (0 if none).',
        ];
    }
}
