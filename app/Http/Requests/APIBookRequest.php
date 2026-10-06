<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class APIBookRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|regex:/^[0-9]{10,13}$/|unique:books,isbn,',
            'published_date' => 'required|date',
            'genres' => 'required|array',
            'image_url' => 'nullable|url'
        ];
    }

    public function messages()
    {
        return[
            'title.required' => 'タイトルを入力してください',
            'author.required' => '著者を入力してください',
            'isbn.required' => 'ISBNを入力してください',
            'isbn.regex' => '13桁で入力してください',
            'isbn.unique' => 'すでに登録されています',
            'published_date.required' => '出版日を選択してください',
            'genres.required' => 'ジャンルを選択してください' 
        ];
    }
}
