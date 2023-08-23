<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;

class UserImageRequest extends FormRequest
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
        return ($this->user() && $this->check('can-access', $this->userImage));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $i = $this->method();
        if ($i === 'POST' && !$this->userImage) {
            return [
                'image' => 'required|image|max:20240',
                'car_id' => 'integer|nullable',
                'visible_name' => 'string|nullable',
                'favorite' => 'boolean|nullable',
                'meta_data' => 'array|nullable',
            ];
        } else if($i === "POST" && $this->userImage) {
            return [
                'car_id' => 'integer|nullable',
                'visible_name' => 'string|nullable',
                'favorite' => 'boolean|nullable',
                'meta_data' => 'array|nullable',
            ];
        }
        return [];
    }

}
