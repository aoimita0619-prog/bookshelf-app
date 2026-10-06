<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReadingPlanRequest extends FormRequest
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
            'target_date' => 'required|date',
            'book_id' => 'required',
        ];
    }

     public function messages()
    {
        return[
            'target_date.required' => '期日を入力してください',
            'book_id.required' => '本を選択してください'
        ];
    }
}
