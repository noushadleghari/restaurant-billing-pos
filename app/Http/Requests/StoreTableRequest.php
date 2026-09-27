<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:30', 'unique:dining_tables,name'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a table name/number.',
            'name.unique' => 'This table name already exists.',
            'capacity.required' => 'Please enter seating capacity.',
            'capacity.integer' => 'Capacity must be a whole number.',
        ];
    }
}
