<?php
namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;

class UserGroupMap extends BaseModel
{
    protected static $record_changer = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_groups_maps';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'group_id',
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

}