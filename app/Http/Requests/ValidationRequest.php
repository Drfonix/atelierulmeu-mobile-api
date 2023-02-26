<?php

namespace App\Http\Requests;

use App\Models\AuthRequest;
use App\Rules\CodeExists;
use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (boolean)!$this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "code" => ["required", "string", new CodeExists],
            "phone" => ["required", "string", new ValidPhoneNumber],
            "type" => ["required", "string", Rule::in(AuthRequest::TYPES)]
        ];
    }
}
