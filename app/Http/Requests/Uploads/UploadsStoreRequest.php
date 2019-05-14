<?php

namespace App\Http\Requests\Uploads;

use Illuminate\Foundation\Http\FormRequest;

class UploadsStoreRequest extends FormRequest
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
            "image" => "required|image|max:2048",
            "merchant_extra_id" => "integer",
            "experience_id" => "integer"
        ];
    }
}
