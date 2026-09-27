<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'sku' => ['nullable', 'string', 'max:50', 'unique:products,sku'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a product name.',
            'category_id.required' => 'Please select or add a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'price.required' => 'Please enter a price.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be greater than 0.',
            'sku.unique' => 'This SKU is already used by another product.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be jpg, jpeg, png or webp.',
            'image.max' => 'Image must not be larger than 2MB.',
        ];
    }
}
