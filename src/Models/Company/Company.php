<?php

namespace DaydreamLab\User\Models\Company;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\User\UserCompany;

class Company extends BaseModel
{
    use UserInfo;
    use RecordChanger {
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
        'status',
        'category_id',
        'vat',
        'domain',
        'mailDomains',
        'salesInfo',
        'logo',
        'phoneCode',
        'phone',
        'phones',
        'industry',
        'scale',
        'country',
        'state_',
        'city',
        'district',
        'address',
        'approveAt',
        'expiredAt',
        'rejectedAt',
        'zipcode',
        'introtext',
        'description',
        'ordering',
        'locked_at',
        'locked_by',
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
        'category_name'
    ];


    protected $casts = [
        'industry'      => 'array',
        'mailDomains'   => 'array',
        'salesInfo'     => 'array',
        'phones'        => 'array',
        'category_id'   => 'integer',
        'locked_at'     => 'datetime:Y-m-d H:i:s',
    ];


    public static function boot()
    {
        self::traitBoot();

        static::creating(function ($item) {
            $item->phoneCode = '+886';
            $item->country = '臺灣';
            if (!$item->status) {
                $item->status = EnumHelper::COMPANY_NEW;
            }
            if (!$item->mailDomains) {
                $item->mailDomains = [
                    'domain' => [],
                    'email'  => []
                ];
            }
        });
    }


    public function category()
    {
        return $this->belongsTo(CompanyCategory::class, 'category_id', 'id');
    }


    public function userCompanies()
    {
        return $this->hasMany(UserCompany::class, 'company_id', 'id');
    }


    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->title : '';
    }
}
