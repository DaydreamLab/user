<?php
namespace DaydreamLab\User\Models\Role;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Asset\AssetApi;
use Kalnoy\Nestedset\NodeTrait;

class Role extends BaseModel
{
    use NodeTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    protected $ordering = 'asc';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'redirect',
        'state',
        'order',
        'canDelete',
        'created_by',
        'updated_by',
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
        'assets',
        'apis',
        'tree_title'
    ];


    public function api()
    {
        return $this->belongsToMany(AssetApi::class, 'roles_apis_maps', 'role_id', 'api_id');
    }


    public function asset()
    {
        return $this->belongsToMany(Asset::class, 'roles_assets_maps', 'role_id', 'asset_id');
    }


    public function getApisAttribute()
    {
        return $this->api()->get();
    }


    public function getAssetsAttribute()
    {
        return $this->asset()->get();
    }

}