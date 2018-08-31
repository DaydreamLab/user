<?php

namespace DaydreamLab\User\Models\User;


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

    protected static $limit = 25;

    protected static $ordering = 'asc';

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
    ];

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


    public static function changePassword($user, $input)
    {
        if (!Hash::check($input['old_password'], $user->password)) {
            return 'OLD_PASSWORD_INCORRECT';
        }

        $user->password = bcrypt($input['password']);
        return $user->save();
    }



    public static function findByEmail($email)
    {
        return self::where('email', $email)->first();
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


    public static function getUserRoles($user_id) {
        $user = self::where('id', '=', $user_id)->first();

        if($user->id) {

            $sql = " SELECT users_roles_map.user_id, users_roles_map.role_id, roles.name, roles.enabled, roles.redirect FROM users_roles_map ";
            $sql.= " INNER JOIN roles ";
            $sql.= " ON users_roles_map.role_id = roles.id ";
            $sql.= " WHERE users_roles_map.user_id = $user_id ";

            $data = DB::select($sql);

            //STEP 2 get current user redirect

            $temp = [];
            if( $user->redirect != '' ){
                $temp['default'] = $user->redirect;
                //$data['current_redirect'] = $user->redirect;
            }else{
                $temp['default'] = 'empty';
                //$data['current_redirect'] = 'empty';
            }
            $data[count($data)] = (object) $temp;

            //Helper::show($data);
            //exit();

            return $data;
        }else{
            return [];
        }

    }

    public function getFullNameAttribute()
    {
        return $this->last_name . ' '. $this->first_name;
    }

    public function isAdmin()
    {
        $super_user  = Role::where('name', 'Super User')->first();
        $admin       = Role::where('name', 'Admin')->first();
        $user        = Auth::user();

        foreach ($user->roles()->get() as $role) {
            if ($role->_lft >= $super_user->_lft && $role->_rgt <= $super_user->_rgt) {
                return true;
            }
            elseif ($role->_lft >= $admin->_lft && $role->_rgt <= $admin->_rgt) {
                return true;
            }
        }
        return false;
    }


    public static function modify($id, $input)
    {
        $input['updated_by'] = $id;
        return self::find($id)->update($input);
    }


    public function oauthAccessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles_map');
    }

    public function sendPasswordResetNotification($token)
    {
        $user = self::findByEmail($this->email);
        $user->notify(new ResetPasswordNotification($user, $token));
    }

    public static function setLimit($limit)
    {
        if ($limit && $limit != ''){
            self::$limit = $limit;
        }
    }


    public static function setOrdering($ordering)
    {
        if ($ordering && $ordering != ''){
            self::$ordering = $ordering;
        }
    }

    public static function user($id)
    {
        return self::find($id);
    }


    public static function updateUserData($input, $id) {

        $data = self::user($id);

        if ( $data->update($input) ) {

            return true;
        }else{
            return false;
        }
    }



    public static function updateUserRoles($request) {
        $data = self::user($request->id);

        if( $data->id != 0 ){
            //STEP user_role_map
            //delete
            $map = UserRoleMap::where('user_id', $data->id)->get();
            foreach( $map as $item ) {
                $item->delete();
            }
            //create
            foreach( $request->ids_map as $role_id ){
                UserRoleMap::create([
                    'user_id'   =>  $data->id,
                    'role_id'  =>  $role_id
                ]);
            }
            //STEP 2 update user table redirect
            $data->redirect = $request->redirect;
            $data->save();
        }

        return true;
    }

    public static function getUserbyEmail($email) {
        //Helper::show($input);
        //exit();
        $data = self::where('email', '=', $email )->first();
        return $data;
    }


}
