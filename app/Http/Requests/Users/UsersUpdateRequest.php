<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UsersUpdateRequest extends FormRequest
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
            'first_name' => 'string',
            'signed_in' => 'boolean',
            'surname' => 'string',
            'email' => 'string',
            'subscribed_to_newsletter' => 'boolean',
            'password' => 'string',
            'role' => 'string',
            'company' => 'string',
            'address' => 'string',
            'city' => 'string',
            'country' => 'string',
            'postal_code' => 'integer',
            'profile_image' => 'image|nullable|mimes:jpeg,jfif,png,jpg,gif,svg|max:2048',
        ];
    }
}
