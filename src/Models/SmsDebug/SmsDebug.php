<?php

namespace DaydreamLab\User\Models\SmsHistory;

use DaydreamLab\Dsth\Models\Notification\Notification;
use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Models\User\User;

class SmsDebug extends BaseModel
{
    use RecordChanger {
        RecordChanger::boot as traitBoot;
    }
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
        'response' => 'array'
    ];


    public static function boot()
    {
        self::traitBoot();
    }
}
