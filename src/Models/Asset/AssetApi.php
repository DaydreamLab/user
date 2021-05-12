<?php
namespace DaydreamLab\User\Models\Asset;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\User\Models\User\UserGroup;

class AssetApi extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assets_apis';


    protected $ordering = 'asc';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'asset_id',
        'service',
        'method',
        'url',
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
        'asset_title'
    ];


    protected $casts = [
        'params' => 'array'
    ];


    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'assets_apis_maps', 'api_id', 'asset_id');
    }


    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'users_groups_apis_maps', 'api_id', 'group_id')->withTimestamps();
    }


    public function getAssetAttribute()
    {
        return $this->assets()->first();
    }


    public function getAssetTitleAttribute()
    {
        $asset = $this->assets()->first();
        return $asset ? $asset->title : null;
    }


    public function getUserGroupsAttribute()
    {
        return $this->userGroups()->get();
    }
}