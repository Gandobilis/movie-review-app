<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            "email" => "email|max:255|unique:users,email," . $this->route('user')->id,
            "name" => "string|max:255",
            "password" => "string|min:8|max:128",
            "image" => "file|mimes:png,jpg,jpeg",
            'active' => 'boolean',
        ];
    }
}
