<?php

namespace App\Http\Requests;

use App\Http\Library\UIDTrait;
use Illuminate\Foundation\Http\FormRequest;

class AnswerStoreRequest extends FormRequest
{
    use UIDTrait;

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
            'body' => 'required|string|min:10|max:1500',
        ];
    }

    /**
     * Return the validation error messages that apply to the request
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'body.required' => 'Body cannot be empty.',
            'body:min' => 'Body is too short. minimum 10 characters required.',
            'body:max' => 'Body has to be maximum 1500 characters long.'
        ];
    }

    /**
     * return validated data from request
     *
     * @return array
     * @throws \Exception
     */
    public function validated(): array
    {
        return array_merge(parent::validated(), [
            'unique' => $this->generateAnswerUId(),
            'user_id' => $this->user()->id,
        ]);
    }
}
