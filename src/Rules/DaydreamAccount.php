<?php

namespace DaydreamLab\User\Rules;

use DaydreamLab\JJAJ\Helpers\Helper;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Contracts\Validation\Rule;

class DaydreamAccount implements Rule
{

    protected $emailValidator;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->emailValidator = new EmailValidator();
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
        return $this->emailValidator->isValid($value, new RFCValidation) || preg_match("/^09[0-9]{8}$/", $value) ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be taiwan mobile phone';
    }
}
