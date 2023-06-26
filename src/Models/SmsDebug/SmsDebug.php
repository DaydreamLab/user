<?php

namespace DaydreamLab\User\Models\SmsDebug;

use DaydreamLab\JJAJ\Models\BaseModel;

class SmsDebug extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms_debug';


    protected $name = 'SmsDebug';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'historyId',
        'payload',
        'response'
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
        'payload'   => 'array',
        'response' => 'array'
    ];
}
