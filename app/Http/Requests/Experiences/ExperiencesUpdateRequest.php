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
            'title' => 'string|nullable',
            'slug' => 'string|nullable',
            'merchant_id' => 'integer|nullable',
            'about_merchant' => 'string|nullable',
            'contact_email' => 'string|nullable',
            'location' => 'string|nullable',
            'city' => 'string|nullable',
            'state' => 'string|nullable',
            'offerings' => 'string|nullable',
            'language' => 'string|nullable',
            'country' => 'string|nullable',
            'description' => 'string|nullable',
            'naira_price' => 'integer|nullable',
            'dollar_price' => 'integer|nullable',
            'pounds_price' => 'integer|nullable',
            'meetup_location' => 'string|nullable',
            'price_from' => 'integer|nullable',
            'price_to' => 'integer|nullable',
            'itenary' => 'string|nullable',
            'extra_perks' => 'string|nullable',
            'drink_types' => 'string|nullable',
            'dining_options' => 'string|nullable',
            'has_outdoor_sitting' => 'boolean|nullable',
            'opening_and_closing_hours' => 'string|nullable',
            'cancellation_policy' => 'string|nullable',
            'tourist_expected_items' => 'string|nullable',
            'number_admittable' => 'integer|nullable',
            'experiences_type_id' => 'integer|nullable',
            'history' => 'string|nullable',
            'start_date' => 'date|nullable',
            'end_date' => 'date|nullable',
            'approved' => 'boolean|nullable',
            'vr_video' => 'string|nullable',
        ];
    }
}
