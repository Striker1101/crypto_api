<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $accountId = $this->route('account'); // Get the account ID from the route parameters

        $method = $this->method();
        if ($method == 'PUT') {
            return [
                'user_id' => [
                    'required',
                    'exists:users,id',
                    Rule::unique('accounts', 'user_id')->ignore($accountId), // Ignore the current account during uniqueness check
                ],
                'balance' => 'required|numeric',
                'earning' => 'required|numeric',
                'bonus' => 'required|numeric',
                'trade' => 'sometimes|boolean',
                'account_type' => 'required|in:trading,margin',
                'account_stage' => 'required|in:bronze,silver,gold,premium',
            ];
        } else {

            return [

                'user_id' => [
                    'sometimes',
                    'exists:users,id',
                    Rule::unique('accounts', 'user_id')->ignore($accountId), // Ignore the current account during uniqueness check
                ],
                'balance' => 'sometimes|numeric',
                'earning' => 'sometimes|numeric',
                'bonus' => 'sometimes|numeric',
                'trade' => 'sometimes|boolean',
                'account_type' => 'sometimes|in:trading,margin',
                'account_stage' => 'sometimes|in:bronze,silver,gold,premium',
            ];
        }
    }
}
