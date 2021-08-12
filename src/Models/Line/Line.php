<?php

namespace DaydreamLab\User\Models\Line;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\User\Models\User\User;

class Line extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lines';


    protected $name = 'Line';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_user_id',
        'user_id',
    ];


    protected $casts = [
        'params'    => 'array'
    ];


    public static function newFactory()
    {

    }
    

    public function users()
    {
        return $this->hasMany(User::class, "id", "user_id");
    }
}