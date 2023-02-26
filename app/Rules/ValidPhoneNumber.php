<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPhoneNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return self::is_valid_romanian_phone_number($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid romanian phone number.';
    }

    /**
     * @param string $phoneNumber
     * @return bool
     */
    static function is_valid_romanian_phone_number(string $phoneNumber)
    {
        /**
         * Got the rule from here: https://regexr.com/39fv1
         */
        $rule = '/^(\+4|)?(07[0-8]{1}[0-9]{1}|02[0-9]{2}|03[0-9]{2}){1}?(\s|\.|\-)?([0-9]{3}(\s|\.|\-|)){2}$/';
        return preg_match($rule, $phoneNumber);
    }
}
