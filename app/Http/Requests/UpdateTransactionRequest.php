<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:income,expense',
            'category' => 'required|in:rental,maintenance,other,deposit',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ];
    }
}