<?php

namespace DaydreamLab\User\Helpers;

class CompanyHelper
{
    public static function checkEmailIsDealer($email, $company)
    {
        $input_email = explode('@', $email);
        if (
            isset($input_email[1])
            && (in_array($input_email[1], $company->mailDomains)
                || in_array($email, $company->mailDomains)
            )
        ) {
            return true; // domain 符合，使用者經銷會員
        } else {
            return false; // domain 不符合，使用者一般會員
        }
    }
}
