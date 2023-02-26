<?php

namespace App\Http\Requests;

use App\Models\AuthRequest;
use App\Rules\CodeExists;
use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateCredentialChangeRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            "code" => ["required", "string", new CodeExists],
            "phone" => ["required", "string", new ValidPhoneNumber, "unique:users,phone"],
            "type" => ["required", "string", Rule::in(AuthRequest::TYPES)]
        ];
    }
}
