<?php

namespace DaydreamLab\User\Helpers;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Exceptions\BadRequestException;

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


    /**
     * @param $companyOrder
     * @return mixed
     * @throws BadRequestException
     */
    public static function handleCompanyOrder($companyOrder)
    {
        if ($companyOrder['enable'] == '是') {
            if (
                !$companyOrder['type']
                || !$companyOrder['brands']
                || !is_array($companyOrder['brands'])
                || !count($companyOrder['brands'])
            ) {
                throw new BadRequestException('InputInvalid', [
                    'companyOrder.type' => $companyOrder['type'],
                    'companyOrder.brands' => $companyOrder['brands']
                ]);
            }

            if ($companyOrder['startDate']) {
                $companyOrder['startDate'] = Carbon::parse($companyOrder['startDate'] . '-01', 'Asia/Taipei')
                    ->startOfDay()
                    ->tz(config('app.timezone'))
                    ->toDateTimeString();
            }

            if ($companyOrder['endDate']) {
                $companyOrder['endDate'] = Carbon::parse($companyOrder['endDate'] . '-01', 'Asia/Taipei')
                    ->startOfMonth()
                    ->tz(config('app.timezone'))
                    ->toDateTimeString();
            }
        }

        return $companyOrder;
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
