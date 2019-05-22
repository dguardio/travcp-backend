<?php

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class BookingsStoreRequest extends FormRequest
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
            'merchant_id' => 'integer|nullable',
            'price' => 'integer|nullable',
            'currency' => 'string|nullable',
            'user_id' => 'integer|nullable',
            'start_date' => 'date|nullable',
            'end_date' => 'date|nullable',
            'quantity' => 'integer|nullable',
            'food_menu_ids' => 'string|nullable',
            'experience_id' => 'integer|required',
        ];
    }
}
