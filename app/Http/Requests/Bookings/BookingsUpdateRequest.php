<?php

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class BookingsUpdateRequest extends FormRequest
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
            'merchant_id' => 'integer',
            'price' => 'integer',
            'currency' => 'string',
            'user_id' => 'integer|required',
            'start_date' => 'date',
            'end_date' => 'date',
            'quantity' => 'integer',
            'food_menu_ids' => 'string|',
            'experience_id' => 'integer',
            'address' => 'string',
        ];
    }
}
