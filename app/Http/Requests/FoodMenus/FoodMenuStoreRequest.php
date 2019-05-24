<?php

namespace App\Http\Requests\FoodMenus;

use Illuminate\Foundation\Http\FormRequest;

class FoodMenuStoreRequest extends FormRequest
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
            "restaurant_id" => "integer|required",
            "category_id" => "integer",
            "description" => "string|required",
            "price" => "numeric|required"
        ];
    }
}
