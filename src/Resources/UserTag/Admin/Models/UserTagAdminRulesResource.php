<?php

namespace DaydreamLab\User\Resources\UserTag\Admin\Models;

use DaydreamLab\Dsth\Resources\Notification\Admin\Collections\NotificationAdminSearchResourceCollection;
use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserTagAdminRulesResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $timezone = $request->user('api')->timezone;

        return [
            'basic'         => $this->handleBasic($timezone),
            'company'       => $this['company'],
            'companyOrder'  => $this->handleCompanyOrder($timezone),
            'event'         => $this->handleEvent($timezone),
            'except'        => $this['except'],
            'menu'          => $this->handleMenu($timezone),
            'order'         => $this['order'],
            'coupon'        => $this['coupon']
        ];
    }


    public function handleBasic($tz)
    {
        return $this->dateTransform([
            'createdAtFrom'     => 'Y-m-d',
            'createdAtTo'       => 'Y-m-d',
            'lastLoginAtFrom'   => 'Y-m-d',
            'lastLoginAtTo'     => 'Y-m-d',
            'lastUpdateFrom'     => 'Y-m-d',
            'lastUpdateTo'     => 'Y-m-d',
        ], $this['basic'], $tz);
    }

    public function handleCompanyOrder($tz)
    {
        return $this->dateTransform([
            'startDate'     => 'Y-m',
            'endDate'       => 'Y-m',
        ], $this['companyOrder'], $tz);
    }


    public function handleEvent($tz)
    {
        return $this->dateTransform([
            'startDate'     => 'Y-m-d',
            'endDate'       => 'Y-m-d',
        ], $this['event'], $tz);
    }


    public function handleMenu($tz)
    {
        return $this->dateTransform([
            'startDate'     => 'Y-m-d',
            'endDate'       => 'Y-m-d',
        ], $this['menu'], $tz);
    }


    public function dateTransform($keys, $data, $tz)
    {
        foreach ($keys as $index => $format) {
            if ($data[$index]) {
                $data[$index] = $this->getDateTimeString($data[$index], $tz, $format);
            }
        }

        return $data;
    }
}
