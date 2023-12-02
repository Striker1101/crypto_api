<?php

namespace App\Http\Requests\V1;

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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:user,admin,maintainer',
            'type' => 'required|in:C,I',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Customize image validation
            'phone_number' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'role.in' => 'The selected role is invalid.',
            'profile_image.image' => 'The profile image must be an image file.',
            'profile_image.mimes' => 'The profile image must be in a valid format (jpeg, png, jpg, gif).',
            'profile_image.max' => 'The profile image may not be greater than 2 MB.',
        ];
    }
}
