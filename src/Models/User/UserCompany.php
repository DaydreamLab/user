<?php

namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;
use DaydreamLab\User\Models\Company\Company;

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
        'vat',
        'phoneCode',
        'phone',
        'extNumber',
        'email',
        'department',
        'jobType',
        'jobCategory',
        'jobTitle',
        'industry',
        'scale',
        'purchaseRole',
        'interestedIssue',
        'issueOther',
        'country',
        'state_',
        'city',
        'district',
        'address',
        'zipcode',
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
        'interestedIssue' => 'array'
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
