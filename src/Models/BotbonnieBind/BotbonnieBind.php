<?php

namespace DaydreamLab\User\Models\BotbonnieBind;

use DaydreamLab\User\Models\UserModel;

class BotbonnieBind extends UserModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'botbonnie_binds';


    protected $name = 'BotbonnieBind';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'platform',
        'page_id',
        'botbonnie_user_id',
        'user_id',
    ];


    protected $casts = [
    ];
}