<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RefundOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'refund_amount' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'refund_amount.required' => 'Please enter the amount to refund.',
            'refund_amount.min' => 'Refund amount must be greater than 0.',
        ];
    }

    /**
     * Extra rule that needs the route-bound order, so it can't live in rules().
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $order = $this->route('order');
            if ($order && $this->filled('refund_amount') && (float) $this->refund_amount > (float) $order->total) {
                $validator->errors()->add('refund_amount', 'Refund amount cannot exceed the order total.');
            }
        });
    }
}
