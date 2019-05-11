<?php

namespace App\Http\Requests\Experiences;

use Illuminate\Foundation\Http\FormRequest;

class ExperiencesUpdateRequest extends FormRequest
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
            'slug' => 'string|unique:experiences',
            'merchant_id' => 'integer',
            'about_merchant' => 'string',
            'contact_email' => 'string',
            'location' => 'string',
            'city' => 'string',
            'state' => 'string',
            'offerings' => 'string',
            'language' => 'string',
            'description' => 'string',
            'naira_price' => 'integer|max:15',
            'dollar_price' => 'integer|max:15',
            'pounds_price' => 'integer|max:15',
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
            'experiences_types_id' => 'integer',
            'rating' => 'integer',
            'rating_count' => 'integer',
            'history' => 'string',
        ];
    }
}
