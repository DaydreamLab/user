<?php

namespace DaydreamLab\User\Models\Asset;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
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
        'model',
        'type',
        'state',
        'redirect',
        'icon',
        'showNav',
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


    public static function boot()
    {
        self::traitBoot();
        self::withDepth();
        self::defaultOrder();
    }


    public function apis()
    {
        return $this->hasMany(AssetApi::class, 'asset_id', 'id');
    }


    public function group()
    {
        return $this->belongsToMany(AssetGroup::class, 'assets_groups_maps', 'asset_id', 'group_id');
    }


    public function getApisAttribute()
    {
        return $this->api()->get();
    }


    public function getGroupsAttribute()
    {
        return $this->group()->get();
    }


}