<?php

namespace DaydreamLab\User\Models\Company;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;

class Company extends BaseModel
{
    use UserInfo, RecordChanger {
        RecordChanger::boot as traitBoot;
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';


    protected $name = 'Company';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id',
        'vat',
        'domain',
        'logo',
        'phoneCode',
        'phone',
        'country',
        'state_',
        'city',
        'district',
        'address',
        'zipcode',
        'introtext',
        'description',
        'ordering',
        'created_by',
        'updated_by'
    ];


    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [
    ];


    /**
     * The attributes that should be append for arrays
     *
     * @var array
     */
    protected $appends = [
    ];


    public static function boot()
    {
        self::traitBoot();

        static::creating(function ($item) {
            $item->phoneCode = '+886';
            $item->country = '臺灣';
        });
    }


}
