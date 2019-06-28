<?php
namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Asset\AssetApi;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;

class UserGroup extends BaseModel
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


    public function api()
    {
        return $this->belongsToMany(AssetApi::class, 'users_groups_apis_maps', 'group_id', 'api_id');
    }


    public function canAction($model, $methods)
    {
        $asset = Asset::where('model', $model)->where('type', 'menu')->first();

        if(!$asset) return false;

        // 這個 user group 在這個 asset 可以使用的 apis
        $apis =  $this->belongsToMany( AssetApi::class, 'users_groups_apis_maps', 'group_id', 'api_id')
                        ->where('asset_id', $asset->id)
                        ->get();

        $apis_methods   = $apis->pluck('method');

        return $apis_methods->intersect(collect($methods))->count() === count($methods);
    }


    public function asset($model = null)
    {
        return $this->belongsToMany(Asset::class, 'users_groups_assets_maps', 'group_id', 'asset_id');
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
}