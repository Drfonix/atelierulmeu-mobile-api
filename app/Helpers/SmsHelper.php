<?php

if (!function_exists('is_valid_romanian_phone_number')) {
    /**
     * @param string $phoneNumber
     * @return bool
     */
    function is_valid_romanian_phone_number(string $phoneNumber)
    {
        /**
         * Got the rule from here: https://regexr.com/39fv1
         */
        $rule = '/^(\+4|)?(07[0-8]{1}[0-9]{1}|02[0-9]{2}|03[0-9]{2}){1}?(\s|\.|\-)?([0-9]{3}(\s|\.|\-|)){2}$/';
        return preg_match($rule, $phoneNumber);
    }
}
