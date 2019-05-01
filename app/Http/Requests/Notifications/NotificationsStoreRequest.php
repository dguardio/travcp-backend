<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class NotificationsStoreRequest extends FormRequest
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
            'user_id' => 'integer|required',
            'notification_body' => 'string|required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.required' => 'User id is required',
            'notification_body.required' => 'notification body is required',
        ];
    }
}
