<?php
namespace DaydreamLab\User\Models\Viewlevel;

use DaydreamLab\JJAJ\Models\BaseModel;

class Viewlevel extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'viewlevels';

    protected $order_by = 'id';

    protected $order = 'asc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'rules',
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
        'rules' => 'array'
    ];

}