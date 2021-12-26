<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:10',
            'body' => 'required|string|min:10|max:1500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.min' => 'Title is too short.',
            'body.required' => 'Body cannot be empty.',
            'body:min' => 'Body is too short. minimum 10 characters required.',
            'body:max' => 'Body has to be maximum 1500 characters long.'
        ];
    }
}
