<?php

namespace App\Http\Requests\MerchantPayments;

use Illuminate\Foundation\Http\FormRequest;

class MerchantPaymentsStoreRequest extends FormRequest
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
            'payer_id' => 'integer|required',
            'merchant_id' => 'integer|required',
            'amount' => 'integer|required',
            'currency' => 'string|required',
            'transaction_id' => 'string|unique:merchant_payments|required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'description.required' => 'Description is required',
            'payer_id.required' => 'Payer id is required',
            'merchant_id.required' => 'Merchant id is required',
            'amount.required' => 'Amount is required',
            'currency.required' => 'Currency is required',
            'transaction_id.required' => 'Transaction id is required'
        ];
    }
}
