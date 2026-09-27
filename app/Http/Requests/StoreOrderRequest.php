<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dining_table_id' => ['nullable', 'exists:dining_tables,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'order_type' => ['required', 'in:dine_in,takeaway'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.note' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Please add at least one item to the order.',
            'items.min' => 'Please add at least one item to the order.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
