<?php
namespace DaydreamLab\User\Models\Role;

use DaydreamLab\JJAJ\Models\BaseModel;
use Kalnoy\Nestedset\NodeTrait;

class Role extends BaseModel
{
    use NodeTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'title',
        'state',
        'redirect',
        'canDelete',
        'created_by',
        'updated_by',
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



}