<?php

namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\HasCustomRelation;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\Asset\AssetGroup;
use DaydreamLab\User\Traits\Model\WithAccess;
use Kalnoy\Nestedset\NodeTrait;

class UserGroup extends BaseModel
{
    use NodeTrait, HasCustomRelation, WithAccess,
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
            ->with(['asset_group_id', 'asset_id', 'api_id'])
            ->withTimestamps();
    }


    public function assetGroups($model = null)
    {
        return $this->belongsToMany(AssetGroup::class, 'users_groups_assets_groups_maps', 'user_group_id', 'asset_group_id')
            ->withTimestamps();
    }


    public function defaultAccessGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'users_groups_default_access_maps', 'group_id', 'access_group_id');
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
}
