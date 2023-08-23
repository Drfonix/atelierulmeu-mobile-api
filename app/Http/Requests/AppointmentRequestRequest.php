<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequestRequest extends FormRequest
{
    use AuthorizesRequests {
        authorize as check;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user() && $this->check('can-access', $this->appointment));
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
                    'title' => ['string', 'nullable'],
                    'status' => ['string'],
                    'car_plate_number' => ['string', 'nullable'],
                    'client_name' => ['string', 'nullable'],
                    'car_make_model' => ['string', 'nullable'],
                    'phone' => ['string', 'nullable'],
                    'from' => ['string', 'nullable'],
                    'to' => ['string', 'nullable'],
                    'duration' => ['numeric'],
                    'requested_services' => ['array', 'nullable'],
                    'meta_data' => ['array', 'nullable'],
                    'service_data' => ['array', 'nullable'],
                ];
            default:
                return [];
        }
    }
}
