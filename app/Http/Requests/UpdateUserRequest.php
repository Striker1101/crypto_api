<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        logger($method);
        switch ($method)
        {
            case 'PUT':
                return [
                    'name' => 'required|string|max:255',
                    'email' => "required|email|unique:users,email,{$this->user}",
                    'active' => 'boolean',
                    'type' => 'in:user,admin',
                    'phone_number' => 'nullable|string',
                    'street' => 'required|string',
                    'city' => 'required|string',
                    'state' => 'required|string',
                    'zip_code' => 'required|string',
                    'image_url' => 'nullable|string',
                    'image_id' => 'nullable|integer',
                ];
            default:
                return [
                    'name' => 'sometimes|string|max:255',
                    'active' => 'boolean',
                    'type' => 'in:user,admin',
                    'phone_number' => 'nullable|string',
                    'street' => 'sometimes|string',
                    'city' => 'sometimes|string',
                    'state' => 'sometimes|string',
                    'zip_code' => 'sometimes|string',
                    'image_url' => 'nullable|string',
                    'image_id' => 'nullable|integer',
                ];
        }
    }
}
