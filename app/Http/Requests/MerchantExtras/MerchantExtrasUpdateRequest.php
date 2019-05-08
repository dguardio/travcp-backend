<?php

namespace App\Http\Requests\MerchantExtras;

use Illuminate\Foundation\Http\FormRequest;

class MerchantExtrasUpdateRequest extends FormRequest
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
            "business_email" => "string",
            "identity_document_url" => "string",
            "bio" => "string",
            "phone" => "string",
            "user_id" => "integer",
        ];
    }
}
