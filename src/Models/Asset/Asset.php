<?php
namespace DaydreamLab\User\Models\Asset;

use DaydreamLab\JJAJ\Models\BaseModel;
use Kalnoy\Nestedset\NodeTrait;

class Asset extends BaseModel
{
    use NodeTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assets';

    protected $ordering = 'asc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'path',
        'component',
        'type',
        'state',
        'redirect',
        'icon',
        'showNav',
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
        'pivot'
    ];


    /**
     * The attributes that should be append for arrays
     *
     * @var array
     */
    protected $appends = [
        //'tree_lv',
        'apis',
        'groups'
    ];


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
        return $this->apis()->get();
    }


    public function getGroupsAttribute()
    {
        return $this->group()->get();
    }


//    public function getTreeLvAttribute()
//    {
//        return $this->_rgt - $this->_lft;
//    }

}