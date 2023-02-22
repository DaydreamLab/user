<?php

namespace DaydreamLab\User\Helpers;

use DaydreamLab\User\Models\Company\Company;

class CompanyHelper
{
    public static function checkEmailIsDealer($email, $company)
    {
        $input_email = explode('@', $email);
        if (
            isset($input_email[1])
            && (in_array($input_email[1], $company->mailDomains['domain'])
                || in_array($email, $company->mailDomains['email'])
            )
        ) {
            return true; // domain 符合，使用者經銷會員
        } else {
            return false; // domain 不符合，使用者一般會員
        }
    }


    public static function checkOemByUserEmail($inputUserCompany)
    {
        $domain = explode('@', $inputUserCompany['email'])[1];
        return Company::where('categoryNote', '原廠')
            ->whereJsonContains('mailDomains->domain', [$domain])
            ->first();
    }


    public static function updatePhonesByUserPhones($company, $inputUserCompany)
    {
        if (isset($inputUserCompany['phones'])) {
            $companyPhones = $company->phones ?: [];
            foreach ($inputUserCompany['phones'] ?: [] as $inputUserPhone) {
                if (!in_array($inputUserPhone['phone'], collect($companyPhones)->pluck('phone')->all())) {
                    $companyPhones[] = [
                        'phoneCode' => $inputUserPhone['phoneCode'],
                        'phone' => $inputUserPhone['phone'],
                        'ext'   => ''
                    ];
                }
                $company->phones = $companyPhones;
            }
            $company->save();
        }
    }
}
