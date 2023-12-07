<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
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
        if($method == 'PUT'){
            return [
            'name' => 'required',
            'type' => 'required|in:stock,cryptocurrency,commodity',
            'user_id' => 'required|exists:users,id',
            'image_url'=> '',
            'image_id'=> '',
        ];
    }else{
        return [
            'name' => 'sometimes',
            'type' => 'sometimes|in:stock,cryptocurrency,commodity',
            'user_id' => 'sometimes|exists:users,id',
            'image_url'=> '',
            'image_id'=> '',
        ];
    }
}

}
