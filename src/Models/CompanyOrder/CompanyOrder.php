<?php

namespace DaydreamLab\User\Models\CompanyOrder;

use DaydreamLab\Cms\Models\Brand\Brand;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;
use DaydreamLab\User\Models\Company\Company;
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

    protected $order_by = 'date';

    protected $order = 'desc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'companyId',
        'brandId',
        'date',
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


    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brandId', 'id');
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'companyId', 'id');
    }
}
