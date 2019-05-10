<?php

namespace App\Http\Requests\Experiences;

use Illuminate\Foundation\Http\FormRequest;

class ExperiencesStoreRequest extends FormRequest
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
            'title' => 'string|required',
            'slug' => 'string|unique:experiences|required',
            'merchant_id' => 'integer|required',
            'about_merchant' => 'string',
            'contact_email' => 'string|required',
            'location' => 'string',
            'city' => 'string',
            'state' => 'string',
            'offerings' => 'string',
            'language' => 'string',
            'description' => 'string',
            'naira_price' => 'integer',
            'dollar_price' => 'integer',
            'pounds_price' => 'integer',
            'meetup_location' => 'string',
            'price_from' => 'integer|max:13',
            'price_to' => 'integer|max:13',
            'itenary' => 'string',
            'extra_perks' => 'string',
            'drink_types' => 'string',
            'dining_options' => 'string',
            'has_outdoor_sitting' => 'boolean',
            'opening_and_closing_hours' => 'string',
            'cancellation_policy' => 'string',
            'tourist_expected_items' => 'string',
            'number_admittable' => 'integer',
            'experiences_types_id' => 'integer|required',
            'rating' => 'integer',
            'rating_count' => 'integer',
            'history' => 'string',
        ];
    }
}
