<?php

namespace DaydreamLab\User\Models\UserTag;

use DaydreamLab\Cms\Models\Category\Category;
use DaydreamLab\Dsth\Models\Notification\Notification;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\JJAJ\Traits\UserInfo;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\UserModel;
use Illuminate\Support\Str;

class UserTag extends UserModel
{
    use UserInfo;
    use RecordChanger {
        RecordChanger::boot as traitBoot;
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_tags';


    protected $name = 'UserTag';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'categoryId',
        'title',
        'alias',
        'state',
        'type',
        'botbonnieId',
        'botId',
        'description',
        'rules',
        'created_by',
        'updated_by',
    ];


    protected $casts = [
        'rules'    => 'array'
    ];


    public static function boot()
    {
        self::traitBoot();

        static::creating(function ($model) {
            $model->alias = Str::random(8);
            if (!$model->categoryId) {
                $model->categoryId = Category::where('title', '未分類')
                    ->where('extension', 'usertag')
                    ->first()
                    ->id;
            }
        });
    }


    public static function newFactory()
    {
    }


    public function activeUsers()
    {
        return $this->belongsToMany(User::class, 'users_usertags_maps', 'userTagId', 'userId')
            ->withPivot(['forceAdd', 'forceDelete'])
            ->wherePivot('forceDelete', 0)
            ->with(['userTags', 'monthMarketingMessages'])
            ->withTimestamps();
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'users_usertags_maps', 'userTagId', 'userId')
            ->withPivot(['forceAdd', 'forceDelete'])
            ->withTimestamps();
    }


    public function realTimeActiveUsers($crmSearchUsers)
    {
        return $this->users->where('pivot.forceDelete', 0)
            ->merge($crmSearchUsers->reject(function ($crmUser) {
                return in_array($crmUser->id, $this->users->where('pivot.forceDelete', 1)->pluck('id')->all());
            }))->unique('id')->values();
    }


    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notifications_usertags_maps', 'userTagId', 'notificationId')
            ->withTimestamps();
    }
}
