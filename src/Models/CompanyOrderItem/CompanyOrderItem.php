<?php

namespace DaydreamLab\User\Models\CompanyOrderItem;

use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;
use DaydreamLab\User\Models\UserModel;

class CompanyOrderItem extends UserModel
{
    use UserInfo, RecordChanger {
        RecordChanger::boot as traitBoot;
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_order_items';


    protected $name = 'CompanyOrderItem';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orderId',
        'title',
        'unitPrice',
        'qty',
        'created_by',
        'updated_by',
    ];


    protected $casts = [
        'params'    => 'array'
    ];


    public static function boot()
    {
        self::traitBoot();
    }


    public static function newFactory()
    {

    }
}