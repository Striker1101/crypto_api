<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Models\User;
class StoreAccountRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('accounts', 'user_id'), // Ensure a user has only one account
            ],
            'balance' => 'required|numeric',
            'earning' => 'required|numeric',
            'bonus' => 'required|numeric',
            'account_type' => 'required|in:trading,margin',
            'account_stage' => 'required|in:bronze,silver,gold,premium',
        ];
    }
}
