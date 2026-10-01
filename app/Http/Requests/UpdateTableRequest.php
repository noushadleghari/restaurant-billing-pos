<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        $table = $this->route('table');

        return [
            'name' => [
                'required',
                'string',
                'min:1',
                'max:30',
                Rule::unique('dining_tables', 'name')->ignore($table?->id),
            ],
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
