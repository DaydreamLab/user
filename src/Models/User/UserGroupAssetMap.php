<?php
namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;

class UserGroupAssetMap extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_groups_assets_maps';


    protected $name = 'UserGroupAssetMap';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'asset_id'
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