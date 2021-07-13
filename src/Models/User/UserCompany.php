<?php

namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;

class UserCompany extends BaseModel
{
    use RecordChanger, UserInfo;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_companies';


    protected $ordering = 'asc';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'name',
        'email',
        'phoneCode',
        'phone',
        'mobilePhone',
        'extNumber',
        'country',
        'state_',
        'city',
        'district',
        'address',
        'zipcode',
        'department',
        'jobTitle',
        'quit',
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


    protected $casts = [
    ];
}
