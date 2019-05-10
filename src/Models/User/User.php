<?php

namespace DaydreamLab\User\Models\User;

use DaydreamLab\Cms\Models\Item\Item;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Viewlevel\Viewlevel;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, CanResetPassword;

    protected $order_by = 'id';

    protected $limit = 25;

    protected $order = 'asc';

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
        'locale',
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
        //'redirect',
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
        'password_confirmation',
        'remember_token',
    ];

    protected $appends = [
        'full_name',
        //'roles',
        'groups',
        'viewlevels',
        'access_ids',
    ];


    protected static function boot()
    {
        parent::boot();

        $user = Auth::guard('api')->user();

        static::creating(function ($item) use($user) {
            if ($user) {
                $item->created_by = $user->id;
            }
            else{
                $item->created_by = 1;
            }
        });

        static::updating(function ($item) use ($user) {
            if ($user) {
                $item->updated_by = $user->id;
            }
        });
    }


    public function getAccessIdsAttribute()
    {
        $access_ids = [];

        $user_viewlevels = $this->viewlevels;
        $all = Viewlevel::all();
        foreach ($all as $viewlevel)
        {
            if (count(array_intersect($viewlevel->rules, $user_viewlevels)) === count($viewlevel->rules))
            {
                $access_ids[] = $viewlevel->id;
            }
        }

        return $access_ids;
    }


    public function getFullNameAttribute()
    {
        return $this->last_name . ' '. $this->first_name;
    }


    public function getGroupsAttribute()
    {
        return $this->usergroup()->get();
    }

    public function getLimit()
    {
        return $this->limit;
    }


    public function getOrder()
    {
        return $this->order;
    }


    public function getOrderBy()
    {
        return $this->order_by;
    }


    public function getViewlevelsAttribute()
    {
        $access_groups = [];
        foreach ($this->groups as $group)
        {
            $viewlevel = Viewlevel::where('title', '=', $group->description)->first();
            $access_groups = array_merge($access_groups, $viewlevel->rules);
        }

        return $access_groups;
    }


    public function hasAttribute($attribute)
    {
        return in_array($attribute, $this->fillable);
    }


    public function higherPermissionThan($user_id)
    {
        $compared_user = self::find($user_id);

        return count(array_intersect($compared_user->viewlevels, $this->viewlevels)) === count($compared_user->viewlevels) ;
    }


    public function isAdmin()
    {
        $super_user  = UserGroup::where('title', 'Super User')->first();
        $admin       = UserGroup::where('title', 'Administrator')->first();

        foreach ($this->groups as $group) {
            if ($group->_lft >= $super_user->_lft && $group->_rgt <= $super_user->_rgt) {
                return true;
            }
            elseif ($group->_lft >= $admin->_lft && $group->_rgt <= $admin->_rgt) {
                return true;
            }
        }
        return false;
    }


    public function isSuperUser()
    {
        $super_user  = UserGroup::where('title', 'Super User')->first();

        foreach ($this->groups as $group) {
            if ($group->_lft >= $super_user->_lft && $group->_rgt <= $super_user->_rgt) {
                return true;
            }
        }
        return false;
    }


    public function setLimit($limit)
    {
        if ($limit && $limit != ''){
            $this->limit = $limit;
        }
    }


    public function setOrder($order)
    {
        if ($order && $order != ''){
            $this->order = $order;
        }
    }


    public function setOrderBy($order_by)
    {
        if ($order_by && $order_by != ''){
            $this->order_by = $order_by;
        }
    }


    public function usergroup()
    {
        return $this->belongsToMany(UserGroup::class, 'users_groups_maps', 'user_id', 'group_id');
    }

}
