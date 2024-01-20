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

        // $accountId = $this->route('account'); // Get the account ID from the route parameters
        $method = $this->method();



        // dd($accountId, $method);
        if ($method === 'PUT') {
            return [
                'user_id' => [
                    'required',

                    // Check if user_id exists in the users table
                ],
                'balance' => 'required|numeric',
                'earning' => 'required|numeric',
                'bonus' => 'required|numeric',
                'trade' => 'sometimes|boolean',
                'account_type' => 'required|in:trading,margin',
                'account_stage' => 'required|in:beginner,bronze,silver,gold,premium',
            ];
        } else {

            return [

                'user_id' => [
                    'sometimes',
                    'exists:users,id', // Check if user_id exists in the users table
                ],
                'balance' => 'sometimes|numeric',
                'earning' => 'sometimes|numeric',
                'bonus' => 'sometimes|numeric',
                'trade' => 'sometimes|boolean',
                'account_type' => 'sometimes|in:trading,margin',
                'account_stage' => 'sometimes|in:beginner,bronze,silver,gold,premium',
            ];
        }
    }
}
