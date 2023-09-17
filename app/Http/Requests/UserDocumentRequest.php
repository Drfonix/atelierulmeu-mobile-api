<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;

class UserDocumentRequest extends FormRequest
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
        return ($this->user() && $this->check('can-access', $this->userDocument));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $i = $this->method();
        if ($i === 'POST' && !$this->userDocument) {
            return [
                'document' => 'required|file|max:5120',
                'car_id' => 'integer|nullable',
                'type' => 'required|string',
                'meta_data' => 'json|nullable',
            ];
        } else if($i === "POST" && $this->userDocument) {
            return [
                'car_id' => 'integer|nullable',
                'type' => 'required|string',
                'meta_data' => 'array|nullable',
            ];
        }
        return [];
    }
}
