<?php

namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Database\Factories\UserTagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTag extends BaseModel
{
    use HasFactory, RecordChanger {
        RecordChanger::boot as traitBoot;
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_tags';


    protected $name = 'UserTag';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'alias',
        'description',
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
    ];


    public static function boot()
    {
        self::traitBoot();
    }


    public static function newFactory()
    {
        return UserTagFactory::new();
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'users_tags_maps', 'tag_id', 'user_id')
            ->withTimestamps();
    }
}
