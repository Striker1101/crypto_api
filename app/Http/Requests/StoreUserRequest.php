<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Define your authorization logic here (e.g., who can create a user).
        // For example, you can check the user's role.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user,
            'password' => 'required|string|min:8',
            'password_save' => 'nullable|string|min:8',
            'phone_number' => 'nullable|string',
            'uplink' => 'nullable|string',
            'terms' => 'nullable|boolean',
            'street' => 'nullable|string|',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'country' => 'nullable|string',
        ];
    }
}
