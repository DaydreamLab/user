<?php

namespace DaydreamLab\User\Models\User;

use DaydreamLab\Cms\Models\Brand\Brand;
use DaydreamLab\Cms\Models\Newsletter\Newsletter;
use DaydreamLab\Cms\Models\Tag\Tag;
use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\HasCustomRelation;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;
use DaydreamLab\User\Database\Factories\UserFactory;
use DaydreamLab\User\Models\Viewlevel\Viewlevel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\Collection;
use Laravel\Passport\HasApiTokens;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable,
        Authorizable,
        CanResetPassword,
        MustVerifyEmail,
        Notifiable,
        HasApiTokens,
        RecordChanger,
        HasFactory,
        HasCustomRelation,
        UserInfo;

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
        'backupEmail',
        'password',
        'name',
        'firstName',
        'lastName',
        'nickname',
        'redirect',
        'gender',
        'image',
        'phoneCode',
        'phone',
        'mobilePhoneCode',
        'mobilePhone',
        'birthday',
        'timezone',
        'locale',
        'country',
        'state_',
        'city',
        'district',
        'address',
        'zipcode',
        'blockReason',
        'upgradeReason',
        'activation',
        'activateToken',
        'verificationCode',
        'block',
        'canDelete',
        'resetPassword',
        'lastResetAt',
        'lastPassword',
        'lastSendAt',
        'lastLoginAt',
        'lastLoginIp',
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
    ];

    protected $appends = [
    ];



    public static function boot()
    {
        parent::boot();

        static::creating(function ($item){
            $user = auth('api')->user();
            $item->uuid = Str::uuid();
            $item->activateToken = Str::random(48);
            $item->phoneCode = '+886';
            $item->mobilePhoneCode = '+886';
            $item->country = '臺灣';
            $item->locale = 'zh-Hant';
            $item->timezone = 'Asia/Taipei';
            $item->created_by = $user
                ? $user->id
                : null;
            $item->password = $item->password
                ? $item->password
                : bcrypt(Str::random(8));
            $item->verificationCode = $item->verificationCode
                ? $item->verificationCode
                : bcrypt(Str::random(8));
        });

        static::updating(function ($item) {
            $user = auth('api')->user();
            $item->updated_by = $item->created_by = $user
                ? $user->id
                : null;
        });
    }


    public function accessGroups()
    {
        return $this->groups()
            ->with('defaultAccessGroups')
            ->with('descendants')
            ->with('ancestors');
    }


    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brands_users_maps', 'user_id', 'brand_id')
            ->withTimestamps();
    }


    public function company()
    {
        return $this->hasOne(UserCompany::class, 'user_id', 'id');
    }


    public function companies()
    {
        return $this->hasMany(UserCompany::class, 'user_id', 'id');
    }


    /**
     * @return array
     * 先藉由此使用者屬於哪些會群組，而這些會員再找出隸屬於哪些閱讀權限，
     */
    public function getAccessIdsAttribute()
    {
        $allViewlevels = Viewlevel::with('groups')->get()->all();

        $accessIds = [];
        foreach ($allViewlevels as $viewlevel) {
            $viewlevelGroupIds = $viewlevel->groups->pluck('id');
            if ($viewlevelGroupIds->intersect($this->accessGroupIds)->count() == $viewlevelGroupIds->count()) {
                $accessIds[] = $viewlevel->id;
            }
        }

        return $accessIds;
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


    public function getAccessGroupIdsAttribute()
    {
        $accessGroupIds = collect();

        $this->accessGroups->each(function ($group) use (&$accessGroupIds) {
            if ($group->level) {
                # 子節點所有權限
                $accessGroupIds = $accessGroupIds->merge($group->descendants->pluck('id'));
                $ancestorRoot = $group->ancestors->filter(function ($g) {
                    return $g->level == 0;
                })->first();

                # 根節點本身以外的所有預設權限
                $exceptAncestorRootGroupIds = $ancestorRoot->defaultAccessGroups->where('id', '!=', $ancestorRoot->id)->pluck('id');
                $accessGroupIds = $accessGroupIds->merge($exceptAncestorRootGroupIds);
            } else {
                $accessGroupIds = $accessGroupIds->merge($group->defaultAccessGroups->pluck('id'));
            }
            $accessGroupIds = $accessGroupIds->merge([$group->id]);
        });

        return $accessGroupIds->all();
    }


    public function getFullMobilePhoneAttribute()
    {
        return $this->mobilePhoneCode . '-' . $this->mobilePhone;
    }


    public function getFullNameAttribute()
    {
        return $this->last_name . ' '. $this->first_name;
    }


    public function getGroupsAttribute()
    {
        return $this->groups()->get();
    }


    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }


    public function getIsSuperUserAttribute()
    {
        return $this->isSuperUser();
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


    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }


    public function groups()
    {
        return $this->belongsToMany(UserGroup::class, 'users_groups_maps', 'user_id', 'group_id')
            ->withTimestamps();
    }


    public function hasAttribute($attribute)
    {
        return in_array($attribute, $this->fillable);
    }


    public function higherPermissionThan($locker)
    {
        $lockerAccessGroupIds = $locker->accessGroupIds ?: [];

        return count(array_intersect($lockerAccessGroupIds, $this->accessGroupIds ?: [])) === count($lockerAccessGroupIds) ;
    }


    public function isAdmin()
    {
        $admins = UserGroup::whereIn('title', ['Super User', 'Administrator'])->get();

        return $this
            ->groups()
            ->where(function ($q) use ($admins) {
                foreach ($admins as $admin) {
                    $q->orWhere(function ($q) use ($admin){
                        $q->where('_lft', '>=', $admin->_lft)
                            ->where('_rgt', '<=', $admin->_rgt);
                    });
                }
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

    public static function newFactory()
    {
        return UserFactory::new();
    }


    public function newsletterCategories()
    {
        return $this->belongsToMany(Newsletter::class, 'newsletter_category_user_maps', 'user_id', 'category_id')
            ->withTimestamps();
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


    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'users_tags_maps', 'user_id', 'tag_id')
            ->withTimestamps();
    }
}
