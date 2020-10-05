<?php namespace App\Http\Validators;

use \Illuminate\Validation\Validator;
use Hash;

class HashValidator extends Validator
{
    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateHash($attribute, $value, $parameters)
    {
        return Hash::check($value, $parameters[0]);
    }
}
