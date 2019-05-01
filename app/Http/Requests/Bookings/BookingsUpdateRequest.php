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
            'bookable_type_id' => 'integer',
            'bookable_type_name' => 'string',
            'bookable_id' => 'integer',
            'bookable_name' => 'string',
            'merchant_id' => 'integer',
            'price' => 'integer|max:13',
            'currency' => 'string',
            'user_id' => 'integer|required',
            'start_date' => 'date',
            'end_date' => 'date',
            'quantity' => 'integer',
        ];
    }
}
