<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'device_token' => ['required', 'string'],
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
            'event_id' => ['integer'],
            'car_id' => ['integer'],
        ];
    }
}
