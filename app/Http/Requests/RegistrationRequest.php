<?php

namespace App\Http\Requests;

use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    use AuthorizesRequests {
        authorize as check;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "phone" => ["required", "string", new ValidPhoneNumber],
            "device_token" => ["string", "nullable"]
        ];
    }
}
