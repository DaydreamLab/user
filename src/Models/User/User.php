<?php

namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\HasCustomRelation;
use DaydreamLab\User\Database\Factories\UserFactory;
use DaydreamLab\User\Models\Viewlevel\Viewlevel;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kalnoy\Nestedset\Collection;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, CanResetPassword, HasCustomRelation, HasFactory;

    protected $order_by = 'id';

    protected $limit = 25;

    protected $order = 'asc';

    protected $primaryKey = 'id';


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
        'user_name',
        'nickname',
        'gender',
        'birthday',
        'job',
        'phone_code',
        'phone',
        'country',
        'state',
        'city',
        'district',
        'address',
        'zipcode',

        'identity',
        'unit',
        'unit_department',
        'job_title',
        'school',
        'school_department',
        'grade',
        'how',
        'subscription',

        'image',
        'activation',
        'activate_token',
        //'redirect',
        'block',
        'reset_password',
        'last_reset_at',
        'last_login_at',
        'login_fail_count',
        'timezone',
        'locale',
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

    protected $casts = [
        'how' => 'array'
    ];

    protected $appends = [
        'full_name',
        'groups',
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


    /**
     * @return array
     * 先藉由此使用者屬於哪些會群組，而這些會員再找出隸屬於哪些閱讀權限，
     */
    public function getAccessIdsAttribute()
    {
        $allViewlevels = Viewlevel::with('groups')
            ->with('groups.defaultAccessGroups')
            ->get();

        $accessIds = [];
        foreach ($allViewlevels as $viewlevel) {
            $viewlevelGroupIds = $viewlevel->groups->pluck('id');
            $accessGroupIds = collect();
            $viewlevel->groups->each(function ($group) use (&$accessGroupIds) {
                $accessGroupIds = $accessGroupIds->merge($group->defaultAccessGroups->pluck('id'));
                $accessGroupIds = $accessGroupIds->merge([$group->id]);
            });
            if ($viewlevelGroupIds->intersect($accessGroupIds)->count() == $viewlevelGroupIds->count()) {
                $accessIds[] = $viewlevel->id;
            }
        }

        return $accessIds;
    }


    public function getAccessGroupIdsAttribute()
    {
        $accessGroupIds = collect();
        $this->groups->each(function ($group) use (&$accessGroupIds) {
            $accessGroupIds = $accessGroupIds->merge($group->defaultAccessGroups->pluck('id'));
            $accessGroupIds = $accessGroupIds->merge([$group->id]);
        });

        return $accessGroupIds->all();
    }


    public function getApisAttribute()
    {
        $groups = $this->groups()->with('apis')->get();
        $apis = collect();
        foreach ($groups as $group) {
            $apis = $apis->merge($group->apis);
        }

        return $apis->unique('id')->values();
    }


    public function getAssetsAttribute()
    {
        $groups =  $this->groups()->with('assets')->get();

        $assets = Collection::make();
        foreach ($groups as $group) {
            $assets = $assets->merge($group->assets);
        }

        return $assets->unique('id')->values();
    }


    public function getFullNameAttribute()
    {
        return $this->last_name . ' '. $this->first_name;
    }


    public function getGroupsAttribute()
    {
        return $this->groups()->get();
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


//    public function getViewlevelsAttribute()
//    {
//        $access_groups = [];
//
//        foreach ($this->groups as $group)
//        {
//            $viewlevel = Viewlevel::where('title', '=', $group->description)->first();
//            $access_groups = array_merge($access_groups, $viewlevel->rules);
//        }
//
//        return $access_groups;
//    }


    public function groups()
    {
        return $this->belongsToMany(UserGroup::class, 'users_groups_maps', 'user_id', 'group_id')
            ->withTimestamps();
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

        return $this
            ->groups()
            ->where(function ($q) use ($admin, $super_user) {
                $q->where(function ($q) use ($admin){
                    $q->where('_lft', '>=', $admin->_lft)
                        ->where('_rgt', '<=', $admin->_rgt);
                })->orWhere(function ($q) use ($super_user) {
                    $q->where('_lft', '>=', $super_user->_lft)
                        ->where('_rgt', '<=', $super_user->_rgt);
                });
        })->count();
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


    public static function newFactory()
    {
        return UserFactory::new();
    }


    public function tags()
    {
        return $this->belongsToMany(UserTag::class, 'users_tags_maps', 'user_id', 'tag_id')
            ->withTimestamps();
    }
}
