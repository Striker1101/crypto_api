<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountTypeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'amount' => 'nullable|numeric',
            'spreads' => 'nullable|numeric',
            'leverage' => 'nullable|string',
            'scalping' => 'nullable|boolean',
            'negative_balance_protection' => 'nullable|boolean',
            'stop_out' => 'nullable|string',
            'swap_free' => 'nullable|boolean',
            'minimum_trade_volume' => 'nullable|numeric',
            'hedging_allowed' => 'nullable|boolean',
            'daily_signals' => 'nullable|boolean',
            'video_tutorials' => 'nullable|boolean',
            'dedicated_account_manager' => 'nullable|boolean',
            'daily_market_review' => 'nullable|boolean',
            'financial_plan' => 'nullable|boolean',
            'risk_management_plan' => 'nullable|boolean',
        ];
    }
}
