<?php

namespace DaydreamLab\User\Models\Asset;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\User\UserGroup;
use Kalnoy\Nestedset\NodeTrait;

class Asset extends BaseModel
{
    use NodeTrait,
        RecordChanger {
        RecordChanger::boot as traitBoot;
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assets';


    protected $order = 'asc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'path',
        'full_path',
        'component',
        'type',
        'state',
        'redirect',
        'icon',
        'showNav',
        'description',
        'params',
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
        '_lft',
        '_rgt',
        'pivot',
        'ancestors'
    ];


    /**
     * The attributes that should be append for arrays
     *
     * @var array
     */
    protected $appends = [
        //'apis',
        'groups',
        'tree_title',
        'tree_list_title',
    ];


    protected $casts = [
        'params'    => 'array'
    ];


    public static function boot()
    {
        self::traitBoot();
        self::withDepth();
        self::defaultOrder();
    }


    public function apis()
    {
        return $this->belongsToMany(Api::class, 'assets_apis_maps', 'asset_id', 'api_id')
            ->withPivot(['asset_group_id', 'hidden', 'disabled', 'checked']);
    }


    public function groups()
    {
        return $this->belongsToMany(AssetGroup::class, 'assets_groups_assets_maps', 'asset_id', 'group_id');
    }


    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'users_groups_assets_maps', 'asset_id', 'user_group_id')
            ->withTimestamps();
    }


    public function getApisAttribute()
    {
        return $this->apis()->get();
    }


    public function getGroupsAttribute()
    {
        return $this->groups()->get();
    }
}
