<?php

namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\HasCustomRelation;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\Asset\AssetGroup;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Viewlevel\Viewlevel;
use DaydreamLab\User\Traits\Model\WithAccess;
use Kalnoy\Nestedset\NodeTrait;

class UserGroup extends BaseModel
{
    use NodeTrait,
        //HasCustomRelation,
        WithAccess,
        RecordChanger {
        RecordChanger::boot as traitBoot;
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_groups';

    protected $order_by = 'id';

    protected $order = 'asc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'parent_id',
        'access',
        'description',
        'canDelete',
        'redirect',
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
        //'pivot',
        'created_at',
        'updated_at'
    ];


    /**
     * The attributes that should be append for arrays
     *
     * @var array
     */
    protected $appends = [
        'tree_title',
    ];


    protected $casts = [
        'canDelete' => 'integer'
    ];


    public static function boot()
    {
        self::traitBoot();
    }


    public function apis()
    {
        return $this->belongsToMany(Api::class, 'users_groups_apis_maps', 'group_id', 'api_id')
            ->withPivot(['asset_group_id', 'asset_id'])
            ->withTimestamps();
    }


    public function assetGroups($model = null)
    {
        return $this->belongsToMany(AssetGroup::class, 'users_groups_assets_groups_maps', 'user_group_id', 'asset_group_id')
            ->with('assets', 'assets.apis')
            ->withTimestamps();
    }


    public function assets($model = null)
    {
        return $this->belongsToMany(Asset::class, 'users_groups_assets_maps', 'user_group_id', 'asset_id')
            ->withTimestamps();
    }


    public function defaultAccessGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'users_groups_default_access_maps', 'group_id', 'access_group_id');
    }


    public function getLevelAttribute()
    {
        return $this->ancestors->count();
    }

    /**
     * 組合出群組的權限
     * @return array
     */
    public function getPageAttribute()
    {
        $assetGroupIds = $this->assetGroups->pluck(['id'])->toArray();
        $assetIds = $this->assets->pluck(['id'])->toArray();
        $data = collect();
        $allAssetGroups = AssetGroup::all()->sortBy('ordering');
        $allAssetGroups->each(function ($assetGroup) use (&$data, $assetGroupIds, $assetIds) {
            $tempAssetGroup = $assetGroup->only(['id','site_id', 'title']);
            $tempAssetGroup['path'] = isset($assetGroup->params['path']) ? $assetGroup->params['path'] : '';
            $tempAssetGroup['type'] = isset($assetGroup->params['type']) ? $assetGroup->params['type'] : '';
            $tempAssetGroup['component'] = isset($assetGroup->params['component']) ? $assetGroup->params['component'] : '';
            $tempAssetGroup['icon'] = isset($assetGroup->params['icon']) ? $assetGroup->params['icon'] : '';
            $tempAssetGroup['visible'] = (in_array($assetGroup->id, $assetGroupIds)) ? 1 : 0;

            $assetGroup->assets->each(function ($asset) use ($assetIds, $assetGroup, &$tempAssetGroup) {
                $tempAsset = $asset->only(['id', 'title', 'path', 'component', 'type', 'icon', 'showNav']);
                $tempAsset['visible'] = (in_array($asset->id, $assetIds)) ? 1 : 0;

                $assetApis = $asset->apis->map(function ($assetApi) use ($assetGroup, $asset) {
                    $targetApi = $this->apis()->wherePivot('asset_group_id', $assetGroup->id)
                        ->wherePivot('asset_id', $asset->id)
                        ->wherePivot('api_id', $assetApi->id)
                        ->first();

                    return [
                        'id'        => $assetApi->id,
                        'name'      => $assetApi->name,
                        'method'    => $assetApi->method,
                        'hidden'    => $assetApi->pivot->attributes['hidden'], # 這邊不這樣取會取到 pivot model 的 $hidden[]
                        'disabled'  => $assetApi->pivot->disabled,
                        'checked'   => $targetApi ? 1 : $assetApi->pivot->checked,
                    ];
                })->values();

                $tempAsset['apis'] = $assetApis;
                $tempAssetGroup['assets'][] = collect($tempAsset);
            });

            if (!isset($tempAssetGroup['assets'])) {
                $tempAssetGroup['assets'] = [];
            }
            $data->push(collect($tempAssetGroup));
        });

        return $data->all();
    }


    public function getTreeListTitleAttribute()
    {
        $depth = $this->depth-1;
        $str = '';
        for ($j = 0 ; $j < $depth ; $j++ ) {
            $str .= '-';
        }

        return $depth == 0  ? $this->title : $str . ' '. $this->title;
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'users_groups_maps', 'group_id', 'user_id')
            ->withTimestamps();
    }


    public function viewlevels()
    {
        return $this->belongsToMany(Viewlevel::class, 'viewlevels_users_groups_maps', 'group_id', 'viewlevel_id')
            ->withTimestamps();
    }
}
