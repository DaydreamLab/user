<?php

namespace DaydreamLab\User\Models\User;


use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Role\Role;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, CanResetPassword;

    protected $order_by = 'id';

    protected $limit = 25;

    protected $ordering = 'asc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'email',
        'password',
        'first_name',
        'last_name',
        'redirect',
        'gender',
        'image',
        'phone_code',
        'phone',
        'birthday',
        'timezone',
        'language',
        'school',
        'job',
        'country',
        'state',
        'city',
        'district',
        'address',
        'zipcode',
        'created_by',
        'updated_by',
        'activation',
        'activate_token',
        'redirect',
        'block',
        'reset_password',
        'last_reset_at',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'full_name',
        'roles'
    ];


    public function accessToken()
    {

    }


    protected static function boot()
    {
        parent::boot();

        $user = Auth::guard('api')->user();

        static::creating(function ($item) use($user) {
            if ($user) {
                $item->created_by = $user->id;
            }
        });

        static::updating(function ($item) use ($user) {
            if ($user) {
                $item->updated_by = $user->id;
            }
        });
    }

    public static function getUserApis() {
        //Helper::show( Auth::guard('api')->user()->id );

        //get roles by user_id
        $ids_roles = [];

        $roles = UserRoleMap::where('user_id', '=', Auth::guard('api')->user()->id)->orderBy('role_id', 'asc')->get();
        foreach( $roles as $role ) {
            $ids_roles[] = $role->role_id;
        }
        //Helper::show( $ids_roles );
        //get api_id and group by api_id with roles in roles_assetspis_map table
        $ids_apis = [];

        $assets_apis = RoleAssetapiMap::whereIn('role_id', $ids_roles)->groupBy('api_id')->orderBy('api_id', 'asc')->get();
        //$assets_apis = DB::table('roles_assetapis_map')->whereIn('role_id', $ids_roles)->groupBy('api_id')->orderBy('api_id', 'asc')->get();

        foreach ($assets_apis as $item) {
            $ids_apis[] = $item->api_id;
        }
        //Helper::show( $ids_apis );
        //get api data
        $data = AssetApi::whereIn('id', $ids_apis)->orderBy('id', 'asc')->get();

        $apis = [];
        //$data = self::all();
        /*
                foreach( $data as $item ) {
                    if( !isset( $apis[$item->asset_id] ) ){
                        $temp = [];
                        $temp[] = $item;
                        $apis[$item->asset_id] = $temp;
                    }else{
                        $temp = $apis[$item->asset_id];
                        $temp[] = $item;
                        $apis[$item->asset_id] = $temp;
                    }
                }
        */

        foreach( $data as $item ) {
            if( !isset( $apis[$item->asset_id] ) ){
                $temp = [];
                $temp[$item->method] = true;
                $apis[$item->asset_id] = (object) $temp;
            }else{
                $temp = (array) $apis[$item->asset_id];
                $temp[$item->method] = true;
                $apis[$item->asset_id] = (object) $temp;
            }
        }

        /*
                foreach( $apis as $index ){
                    foreach($index as $item){
                        unset($item->id);
                        unset($item->asset_id);
                        unset($item->url);
                        unset($item->created_by);
                        unset($item->updated_by);
                        unset($item->created_at);
                        unset($item->updated_at);
                    }
                }
        */

        return $apis;
    }


    public function getFullNameAttribute()
    {
        return $this->last_name . ' '. $this->first_name;
    }

    public function getLimit()
    {
        return $this->limit;
    }


    public function getOrdering()
    {
        return $this->ordering;
    }


    public function getRolesAttribute()
    {
        return $this->role()->get();
    }


    public function getOrderBy()
    {
        return $this->order_by;
    }


    public function isAdmin()
    {
        $super_user  = Role::where('title', 'Super User')->first();
        $admin       = Role::where('title', 'Admin')->first();
        $user        = Auth::user();

        foreach ($user->role()->get() as $role) {
            if ($role->_lft >= $super_user->_lft && $role->_rgt <= $super_user->_rgt) {
                return true;
            }
            elseif ($role->_lft >= $admin->_lft && $role->_rgt <= $admin->_rgt) {
                return true;
            }
        }
        return false;
    }


    public function oauthAccessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }


    public function role()
    {
        return $this->belongsToMany(Role::class, 'users_roles_maps', 'user_id', 'role_id');
    }


    public function setLimit($limit)
    {
        if ($limit && $limit != ''){
            $this->limit = $limit;
        }
    }

    public function setOrdering($ordering)
    {
        if ($ordering && $ordering != ''){
            $this->ordering = $ordering;
        }
    }


    public function setOrderBy($order_by)
    {
        if ($order_by && $order_by != ''){
            $this->order_by = $order_by;
        }
    }

}
