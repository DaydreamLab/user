<?php

namespace DaydreamLab\User\Models\NotificationTemplate;

use DaydreamLab\JJAJ\Models\BaseModel;

class NotificationTemplate extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_templates';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channelType',
        'category',
        'type',
        'subject',
        'content',
        'contentHtml',
        'params',
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


}
