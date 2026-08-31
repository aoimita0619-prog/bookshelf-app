<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenruRequest extends FormRequest
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
            'content' => 'request|unique:genrus'
        ];
    }

     public function messages()
    {
        return[
            'content.required' => 'ジャンルを入力してください',
            'content.unique' => 'すでに登録されているジャンルです',
        ];
    }
}
