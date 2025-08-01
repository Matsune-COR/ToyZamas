<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'カテゴリー名は必須です。',
            'sort_order.required' => '表示順は必須です。',
            'sort_order.integer' => '表示順は整数で入力してください。',
        ];
    }

}
