<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class MovieUpdateRequest extends FormRequest
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
            "image" => "file|mimes:png,jpg,jpeg",
            "name" => 'string|max:255',
            'desc' => 'string',
            'genre_ids' => 'array',
            'genre_ids.*' => 'integer|exists:genres,id'
        ];
    }
}
