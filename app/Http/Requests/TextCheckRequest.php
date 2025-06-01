<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TextCheckRequest extends FormRequest
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
            'text' => 'required|string|min:1|max:5000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'text.required' => 'Текст для проверки обязателен',
            'text.min' => 'Текст должен содержать хотя бы один символ',
            'text.max' => 'Текст не может быть длиннее 5000 символов',
        ];
    }
} 