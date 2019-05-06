<?php

namespace App\Http\Requests\ExperienceTypesCategories;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceTypesCategoriesStoreRequest extends FormRequest
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
            'name' => 'string|required',
            'id' => 'integer|required'
        ];
    }
}
