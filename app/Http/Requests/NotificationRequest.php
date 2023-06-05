<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (boolean)$this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
            case 'PUT':
                return [
                    'car_id' => ['integer'],
                    'type' => ['string'],
                    'title' => ['string'],
                    'alert_date' => ['string'],
                    'message' => ['string', 'nullable'],
                    'expiration_date' => ['string', 'nullable'],
                    'meta_data' => ['array', 'nullable'],
                ];
            default:
                return [];
        }
    }
}
