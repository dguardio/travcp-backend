<?php

namespace App\Http\Requests\Medal;

use Illuminate\Foundation\Http\FormRequest;

class MedalUpdateRequest extends FormRequest
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
            "name" => "string",
            "review_threshold" => "integer"
        ];
    }
}
