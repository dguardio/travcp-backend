<?php

namespace App\Http\Requests\OrderItems;

use Illuminate\Foundation\Http\FormRequest;

class OrderItemsStoreRequest extends FormRequest
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
            "order_id" => "integer|required",
            "booking_id" => "integer|required"
        ];
    }
}
