<?php

namespace App\Http\Requests\MerchantExtras;

use Illuminate\Foundation\Http\FormRequest;

class MerchantExtrasStoreRequest extends FormRequest
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
            "business_name" => "string",
            "business_email" => "string|unique:merchant_extras",
            "upload_id" => "integer",
            "bio" => "string",
            "phone" => "string",
            "merchant_id" => "integer|required|unique:merchant_extras",
        ];
    }
}
