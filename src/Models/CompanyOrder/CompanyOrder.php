<?php

namespace DaydreamLab\User\Models\CompanyOrder;

use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;
use DaydreamLab\User\Models\UserModel;
use Illuminate\Support\Str;

class CompanyOrder extends UserModel
{
    use UserInfo, RecordChanger {
        RecordChanger::boot as traitBoot;
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_orders';


    protected $name = 'CompanyOrder';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userId',
        'uuid',
        'orderNum',
        'date',
        'total',
        'created_by',
        'updated_by',
    ];


    protected $casts = [
        'params'    => 'array'
    ];


    public static function boot()
    {
        self::traitBoot();

        static::creating(function ($item) {
            $item->uuid = Str::uuid();
        });
    }


    public static function newFactory()
    {
    }
}
