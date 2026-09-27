<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{7,20}$/', 'unique:customers,phone'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter customer name.',
            'phone.required' => 'Please enter phone number.',
            'phone.regex' => 'Enter a valid phone number (digits only).',
            'phone.unique' => 'A customer with this phone number already exists.',
        ];
    }
}
