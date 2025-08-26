<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => [
                'required',
                'integer',
                Rule::exists('accounts', 'id')->where('user_id', Auth::id()),
            ],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where('user_id', Auth::id()),
            ],
            'type' => [
                'required',
                'string',
                Rule::in(['income', 'expense']),
            ],
            'date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999999999.99',
            ],
            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'counterparty' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'account_id.exists' => 'The selected account does not exist or does not belong to you.',
            'category_id.exists' => 'The selected category does not exist or does not belong to you.',
            'type.in' => 'Transaction type must be either income or expense.',
            'date.before_or_equal' => 'Transaction date cannot be in the future.',
            'amount.min' => 'Transaction amount must be at least 0.01.',
            'amount.max' => 'Transaction amount is too large.',
            'note.max' => 'Note cannot exceed 1000 characters.',
            'counterparty.max' => 'Counterparty name cannot exceed 255 characters.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check if category type matches transaction type
            if ($this->has('category_id') && $this->has('type')) {
                $category = \App\Models\Category::where('id', $this->category_id)
                    ->where('user_id', Auth::id())
                    ->first();

                if ($category && $category->type !== $this->type) {
                    $validator->errors()->add('category_id', 'Category type must match transaction type.');
                }
            }
        });
    }
}
