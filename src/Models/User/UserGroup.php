<?php
namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\HasCustomRelation;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Asset\AssetApi;
use Kalnoy\Nestedset\NodeTrait;

class UserGroup extends BaseModel
{
    use NodeTrait, HasCustomRelation,
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
        'ancestors',
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


    public function apis()
    {
        return $this->belongsToMany(AssetApi::class, 'users_groups_apis_maps', 'group_id', 'api_id');
    }


    public function canAction($service, $method, $model)
    {
        $apis = $this->apis()->where('service', $service)->where('method', $method)->get();
        if ($apis->count() == 1) {
            return true;
        } elseif ($apis->count() > 1) {
            // 以現在架構並不存在可以編輯自己或別人的這種可能，這邊有問題！
            foreach ($apis as $api) {
                if (strpos($api->method, 'Own')) {
                    return $model->created_by == $this->user->id ?: false;
                } else {
                    return $model->created_by != $this->user->id ?: false;
                }
            }
        }
        else {
            return false;
        }
    }


    public function assets($model = null)
    {
        return $this->belongsToMany(Asset::class, 'users_groups_assets_maps', 'group_id', 'asset_id')
            ->withTimestamps();
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
        return $this->belongsToMany(User::class, 'users_groups_maps', 'group_id', 'user_id');
    }
}