<?php

namespace App\Http\Requests\UserPayments;

use Illuminate\Foundation\Http\FormRequest;

class UserPaymentsStoreRequest extends FormRequest
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
            'description' => 'string|required',
            'user_id' => 'integer|required',
            'experience_id' => 'integer|required',
            'transaction_id' => 'string|required|unique:user_payments',
            'amount' => 'integer|required',
            'currency' => 'string|required', // naira, dollar, pound
        ];
    }

    /**
     * Error Messages
     * @return array
     */
    public function messages()
    {
        return [
            'description' => 'Description is required',
            'user_id.required' => 'User id is required',
            'experience_id.required' => 'Experience id is required',
            'transaction_id.required' => 'Transaction id is required',
            'amount.required' => 'Amount is required',
            'currency.required' => 'Currency is required',
        ];
    }
}
