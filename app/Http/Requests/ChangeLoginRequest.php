<?php

namespace App\Http\Requests;

use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class ChangeLoginRequest extends FormRequest
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
        return [
            "old_phone" => ["required", "string", new ValidPhoneNumber, "exists:users,phone"],
            "new_phone" => ["required", "string", new ValidPhoneNumber, "unique:users,phone"],
        ];
    }
}
