<?php
namespace DaydreamLab\User\Models\Asset;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Models\User\UserGroup;

class AssetGroup extends BaseModel
{
    use RecordChanger {
        RecordChanger::boot as traitBoot;
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assets_groups';

    protected $ordering = 'asc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'state',
        'description',
        'ordering',
        'params',
        'created_by',
        'updated_by'
    ];


    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [
        'pivot'
    ];


    /**
     * The attributes that should be append for arrays
     *
     * @var array
     */
    protected $appends = [
    ];


    protected $casts = [
        'state'     => 'integer',
        'params'    => 'array'
    ];


    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'assets_groups_assets_maps', 'group_id', 'asset_id');
    }


    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'users_groups_assets_groups_maps', 'asset_group_id', 'user_group_id')
            ->withTimestamps();
    }
}
