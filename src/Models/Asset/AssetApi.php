<?php
namespace DaydreamLab\User\Models\Asset;

use DaydreamLab\JJAJ\Models\BaseModel;

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
    ];


    /**
     * The attributes that should be append for arrays
     *
     * @var array
     */
    protected $appends = [
        'asset',
        'asset_name'
    ];


    public function asset()
    {
        return $this->belongsToMany(Asset::class, 'assets_apis_maps', 'api_id', 'asset_id');
    }


    public function getAssetAttribute()
    {
        return $this->asset()->first();
    }


    public function getAssetNameAttribute()
    {
        $asset = $this->asset()->first();
        return $asset ? $asset->title : null;
    }

}