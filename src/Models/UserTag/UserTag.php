<?php

namespace DaydreamLab\User\Models\UserTag;

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
        'title',
        'alias',
        'state',
        'type',
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
        });
    }


    public static function newFactory()
    {
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'users_usertags_maps', 'userTagId', 'userId')
            ->withPivot(['forceAdd', 'forceDelete'])
            ->withTimestamps();
    }


    public function activeUsers()
    {
        return $this->belongsToMany(User::class, 'users_usertags_maps', 'userTagId', 'userId')
            ->withPivot(['forceAdd', 'forceDelete'])
            ->wherePivot('forceDelete', 0)
            ->withTimestamps();
    }
}
