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
            "business_name" => "string|required",
            "business_email" => "string|required|unique:merchant_extras",
            "identity_document_url" => "string|required",
            "bio" => "string|required",
            "phone" => "string|required",
            "user_id" => "integer|required|unique:merchant_extras",
        ];
    }
}
