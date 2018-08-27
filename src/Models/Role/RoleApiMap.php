<?php
namespace DaydreamLab\User\Models\Role;

use DaydreamLab\JJAJ\Models\BaseModel;

class RoleApiMap extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles_apis_maps';

    protected $ordering = 'asc';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'api_id',
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
        'method',
        'asset_id',
        'asset_name',
    ];

    public function AssetApi()
    {
        return $this->belongsTo('DaydreamLab\User\Models\Asset\AssetApi', 'api_id');
    }

    public function getMethodAttribute()
    {
        return $this->AssetApi()->first()->method;
        //return $this->AssetApi()->first();
    }

    public function getAssetIdAttribute()
    {
        return $this->AssetApi()->first()->asset_id;
        //return $this->AssetApi()->first();
    }

    public function getAssetNameAttribute()
    {
        //return $this->asset()->first()->method;
        //ver1
        //return $this->AssetApi()->first()->Asset()->first()->name;
        //ver2
        return $this->AssetApi()->first()->asset_name;
        //Helper::show($this->AssetApi()->first()->toArray());
        //exit();
    }

    public static function getRoleApis($role_id)
    {

        $active_apis = self::where('role_id', '=', $role_id)->get();

        $data = [];

        foreach ($active_apis as $api) {
            $data[] = $api->api_id;
        }

        return $data;
    }

    public static function getRoleAction($role_id)
    {


        if( $role_id != 1 ) {
            $active_apis = self::where('role_id', '=', $role_id)->get();

            $data = [];
            $check_asset_id = [];

            foreach( $active_apis as $item ) {
                if( !in_array($item->asset_id, $check_asset_id) ) {
                    $temp = [
                        'name' => $item->asset_name,
                        'asset_id'   => $item->asset_id,
                        'disabled'   => true,
                        'child'      => ''
                    ];
                    $data[] = (object) $temp;
                    $check_asset_id[] = $item->asset_id;
                }

            }


            foreach( $active_apis as $api ) {
                foreach( $data as $item ) {

                    if( $api->asset_id == $item->asset_id ){
                        if( empty( $item->child ) ) {
                            $temp = [];
                            $single_api = [
                                'id'     => $api->api_id,
                                'name' => $api->method
                            ];
                            $temp[] = (object) $single_api;
                            $item->child = $temp;
                        }else{
                            $temp = $item->child;
                            $single_api = [
                                'id'     => $api->api_id,
                                'name' => $api->method
                            ];
                            $temp[] = (object) $single_api;
                            $item->child = $temp;
                        }

                    }

                }
            }
        }else{

            $apis = AssetApi::all();
            //Helper::show($apis->toArray());
            //exit();
            $data = [];
            $check_asset_id = [];

            foreach( $apis as $item ) {
                if( !in_array($item->asset_id, $check_asset_id) ) {
                    $temp = [
                        'name' => $item->asset_name,
                        'asset_id'   => $item->asset_id,
                        'disabled'   => true,
                        'child'      => ''
                    ];
                    $data[] = (object) $temp;
                    $check_asset_id[] = $item->asset_id;
                }
            }

            foreach( $apis as $api ) {
                foreach( $data as $item ) {

                    if( $api->asset_id == $item->asset_id ){
                        if( empty( $item->child ) ) {
                            $temp = [];
                            $single_api = [
                                'id'     => $api->id,
                                'name' => $api->method
                            ];
                            $temp[] = (object) $single_api;
                            $item->child = $temp;
                        }else{
                            $temp = $item->child;
                            $single_api = [
                                'id'     => $api->id,
                                'name' => $api->method
                            ];
                            $temp[] = (object) $single_api;
                            $item->child = $temp;
                        }
                    }

                }
            }

        } //end else

        return $data;
    }

    public static function updateRoleApis($request)
    {
        $data = Role::where('id', '=', $request->id)->first();
        if ($data->count() > 0) {
            //STEP role_asset_map
            if (!empty($request->ids_map)) {

                //delete
                $map = self::where('role_id', $data->id)->get();
                if (count($map) > 0) {
                    foreach ($map as $item) {
                        $item->delete();
                    }
                }
                //create
                if (count($request->ids_map) > 0) {
                    foreach ($request->ids_map as $api_id) {
                        self::create([
                            'role_id' => $data->id,
                            'api_id' => $api_id,
                        ]);
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

}