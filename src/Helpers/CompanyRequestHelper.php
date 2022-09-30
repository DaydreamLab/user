<?php

namespace DaydreamLab\User\Helpers;

class CompanyRequestHelper
{
    public static function handleMailDomains($input)
    {
        return  [
            'domain' => $input['domain'] ?? [],
            'email' => $input['email'] ?? []
        ];
    }


    public static function handlePhones($input)
    {
        $data = [];
        foreach ($input ?? [] as $inputPhone) {
            $data[] = [
                'phoneCode' => $inputPhone['phoneCode'] ?? '',
                'phone' => $inputPhone['phone'] ?? '',
                'ext' => $inputPhone['ext'] ?? '',
            ];
        }

        return $data;
    }


    //    public function handleSalesInfo($input)
//    {
//        $data = [];
//        foreach ($input ?? [] as $sales) {
//            $data [] = [
//                'name' => $sales['name'] ?? '',
//                'mobilePhone' => $sales['mobilePhone'] ?? '',
//                'email' => $sales['email'] ?? '',
//            ];
//        }
//
//        return $data;
//    }
}
