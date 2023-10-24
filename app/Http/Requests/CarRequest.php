<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
        return ($this->user() && $this->check('can-access', $this->car));
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
                    'name' => ['string', "nullable"],
                    'plate_number' => ['string', 'nullable'],
                    'category' => ['string', 'nullable'],
                    'subcategory' => ['string', 'nullable'],
                    'registration_type' => ['string', 'nullable'],
                    'fuel_type' => ['string', 'nullable'],
                    'vin_number' => ['string', 'nullable'],
                    'make' => ['string', 'nullable'],
                    'model' => ['string', 'nullable'],
                    'manufacture_year' => ['string', 'nullable'],
                    'tyre_size' => ['array', 'nullable'],
                    'motor_power' => ['string', 'nullable'],
                    'cylinder_capacity' => ['string', 'nullable'],
                    'number_places' => ['string', 'nullable'],
                    'max_per_mass' => ['string', 'nullable'],
                    'civ_number' => ['string', 'nullable'],
                    'description' => ['string', 'nullable'],
                    'favorite' => ['boolean', 'nullable'],
                ];
            default:
                return [];
        }
    }
}
