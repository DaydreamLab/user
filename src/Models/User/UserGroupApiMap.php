<?php
namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;

class UserGroupApiMap extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_groups_apis_maps';


    protected $name = 'UserGroupApiMap';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'api_id'
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
    ];


    public function AssetApi()
    {
        return $this->belongsTo('DaydreamLab\User\Models\Asset\AssetApi', 'api_id');
    }


    public function getMethodAttribute()
    {
        $api = $this->AssetApi()->first();
        return $api ? $api->method : null;
    }


//    public static function getRoleApis($role_id)
//    {
//        $active_apis = self::where('role_id', '=', $role_id)->get();
//
//        $data = [];
//
//        foreach ($active_apis as $api) {
//            $data[] = $api->api_id;
//        }
//
//        return $data;
//    }
//
//    public static function getRoleAction($role_id)
//    {
//        if( $role_id != 1 ) {
//            $active_apis = self::where('role_id', '=', $role_id)->get();
//
//            $data = [];
//            $check_asset_id = [];
//
//            foreach( $active_apis as $item ) {
//                if( !in_array($item->asset_id, $check_asset_id) ) {
//                    $temp = [
//                        'name' => $item->asset_name,
//                        'asset_id'   => $item->asset_id,
//                        'disabled'   => true,
//                        'child'      => ''
//                    ];
//                    $data[] = (object) $temp;
//                    $check_asset_id[] = $item->asset_id;
//                }
//
//            }
//
//
//            foreach( $active_apis as $api ) {
//                foreach( $data as $item ) {
//
//                    if( $api->asset_id == $item->asset_id ){
//                        if( empty( $item->child ) ) {
//                            $temp = [];
//                            $single_api = [
//                                'id'     => $api->api_id,
//                                'name' => $api->method
//                            ];
//                            $temp[] = (object) $single_api;
//                            $item->child = $temp;
//                        }else{
//                            $temp = $item->child;
//                            $single_api = [
//                                'id'     => $api->api_id,
//                                'name' => $api->method
//                            ];
//                            $temp[] = (object) $single_api;
//                            $item->child = $temp;
//                        }
//
//                    }
//
//                }
//            }
//        }else{
//
//            $apis = AssetApi::all();
//            //Helper::show($apis->toArray());
//            //exit();
//            $data = [];
//            $check_asset_id = [];
//
//            foreach( $apis as $item ) {
//                if( !in_array($item->asset_id, $check_asset_id) ) {
//                    $temp = [
//                        'name' => $item->asset_name,
//                        'asset_id'   => $item->asset_id,
//                        'disabled'   => true,
//                        'child'      => ''
//                    ];
//                    $data[] = (object) $temp;
//                    $check_asset_id[] = $item->asset_id;
//                }
//            }
//
//            foreach( $apis as $api ) {
//                foreach( $data as $item ) {
//
//                    if( $api->asset_id == $item->asset_id ){
//                        if( empty( $item->child ) ) {
//                            $temp = [];
//                            $single_api = [
//                                'id'     => $api->id,
//                                'name' => $api->method
//                            ];
//                            $temp[] = (object) $single_api;
//                            $item->child = $temp;
//                        }else{
//                            $temp = $item->child;
//                            $single_api = [
//                                'id'     => $api->id,
//                                'name' => $api->method
//                            ];
//                            $temp[] = (object) $single_api;
//                            $item->child = $temp;
//                        }
//                    }
//
//                }
//            }
//
//        } //end else
//
//        return $data;
//    }
//
//    public static function updateRoleApis($request)
//    {
//        $data = Role::where('id', '=', $request->id)->first();
//        if ($data->count() > 0) {
//            //STEP role_asset_map
//            if (!empty($request->ids_map)) {
//
//                //delete
//                $map = self::where('role_id', $data->id)->get();
//                if (count($map) > 0) {
//                    foreach ($map as $item) {
//                        $item->delete();
//                    }
//                }
//                //create
//                if (count($request->ids_map) > 0) {
//                    foreach ($request->ids_map as $api_id) {
//                        self::create([
//                            'role_id' => $data->id,
//                            'api_id' => $api_id,
//                        ]);
//                    }
//                }
//            }
//            return true;
//        } else {
//            return false;
//        }
//    }

}