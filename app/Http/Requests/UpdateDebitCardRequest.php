<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDebitCardRequest extends FormRequest
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
        $method = $this->method();
        if ($method == 'PUT') {

            return [
                'user_id' => 'required|exists:users,id',
                'card_number' => 'required|string',
                'expiration_date' => 'required|date_format:Y-m-d',
                'cvv' => 'required|string',
                'type' => 'required|in:verve,master,visa,black',
            ];

        } else {
            return [
                'user_id' => 'sometimes|exists:users,id',
                'card_number' => 'sometimes|string',
                'expiration_date' => 'sometimes|date_format:Y-m-d',
                'cvv' => 'sometimes|string',
                'type' => 'sometimes|in:verve,master,visa,black',
            ];
        }
    }
}
