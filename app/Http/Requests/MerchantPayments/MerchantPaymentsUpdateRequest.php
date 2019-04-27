<?php

namespace App\Http\Requests\MerchantPayments;

use Illuminate\Foundation\Http\FormRequest;

class MerchantPaymentsUpdateRequest extends FormRequest
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
            'description' => 'string',
            'payer_id' => 'integer',
            'merchant_id' => 'integer',
            'amount' => 'integer',
            'currency' => 'string',
            'transaction_id' => 'unique'
        ];
    }

//    public function messages()
//    {
//        return [
//            'description.required' => 'Description is required',
//            'payer_id.required' => 'Payer id is required',
//            'merchant_id.required' => 'Merchant id is required',
//            'amount.required' => 'Amount is required',
//            'currency.required' => 'Currency is required',
//            'transaction_id.required' => 'Transaction id is required'
//        ];
//    }
}
