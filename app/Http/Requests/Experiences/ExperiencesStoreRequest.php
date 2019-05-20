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
            'title' => 'string',
            'slug' => 'string',
            'merchant_id' => 'integer',
            'about_merchant' => 'string',
            'contact_email' => 'string',
            'location' => 'string',
            'city' => 'string',
            'state' => 'string',
            'offerings' => 'string',
            'language' => 'string',
            'country' => 'string',
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
            'experiences_type_id' => 'integer',
            'rating' => 'integer',
            'rating_count' => 'integer',
            'history' => 'string',
            'start_date' => 'date',
            'end_date' => 'date',
            'approved' => 'boolean',

        ];
    }
}
