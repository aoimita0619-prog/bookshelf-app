<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenreEditRequest extends FormRequest
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
        $genre = $this->route('genre');
        
        return [
            'name' => 'required|unique:genres,name,' . $genre->id,
        ];
    }

     public function messages()
    {
        return[
            'name.required' => 'ジャンルを入力してください',
            'name.unique' => 'すでに登録されているジャンルです',
        ];
    }
}
