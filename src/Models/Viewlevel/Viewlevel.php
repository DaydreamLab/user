<?php
namespace DaydreamLab\User\Models\Viewlevel;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Models\User\UserGroup;

class Viewlevel extends BaseModel
{
    use RecordChanger {
        RecordChanger::boot as traitBoot;
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'viewlevels';

    protected $order_by = 'ordering';

    protected $order = 'asc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'canDelete',
        'description',
        'ordering',
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


    protected $casts = [
    ];


    public static function boot()
    {
        self::traitBoot();
    }


    public function groups()
    {
        return $this->belongsToMany(UserGroup::class, 'viewlevels_users_groups_maps', 'viewlevel_id', 'group_id')
            ->withTimestamps();
    }
}