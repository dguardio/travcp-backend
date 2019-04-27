<?php

namespace App\Http\Requests\Reviews;

use Illuminate\Foundation\Http\FormRequest;

class ReviewsStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'integer|required',
            'experience_id' => 'integer|required',
            'review_body' => 'string|required',
        ];
    }

    /**
     * Error Messages
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.required' => 'User id is required',
            'experience_id.required' => 'Experience id is required',
            'review_body.required' => 'Review body is required',
        ];
    }
}
