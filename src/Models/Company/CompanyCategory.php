<?php

namespace DaydreamLab\User\Models\Company;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;
use DaydreamLab\User\Models\User\UserGroup;
use Kalnoy\Nestedset\NodeTrait;

class CompanyCategory extends BaseModel
{
    use NodeTrait, UserInfo, RecordChanger {
        RecordChanger::boot as traitBoot;
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies_categories';


    protected $name = 'CompanyCategory';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'alias',
        'state',
        'introimage',
        'introtext',
        'image',
        'description',
        'hits',
        'access',
        'ordering',
        'params',
        'extrafields',
        'extrafields_search',
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
        '_lft',
        '_rgt'
    ];


    /**
     * The attributes that should be append for arrays
     *
     * @var array
     */
    protected $appends = [
    ];


    protected $casts = [
        'params'        => 'array',
        'extrafields'   => 'array'
    ];


    public static function boot()
    {
        self::traitBoot();
    }


    public function userGroup()
    {
        return $this->hasOne(UserGroup::class, 'title', 'title');
    }


    public function getUserGroupIdAttribute()
    {
        return $this->userGroup ?
            $this->userGroup->id
            : UserGroup::where('title', '=', '一般會員')->first()->id;
    }
}
