<?php

namespace DaydreamLab\User\Models\SmsHistory;

use DaydreamLab\Dsth\Models\Notification\Notification;
use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Traits\RecordChanger;
use DaydreamLab\User\Models\User\User;

class SmsHistory extends BaseModel
{
    use RecordChanger {
        RecordChanger::boot as traitBoot;
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms_histories';


    protected $name = 'SmsHistory';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notificationId',
        'phoneCode',
        'phone',
        'category',
        'type',
        'messageId',
        'message',
        'messageCount',
        'messageLength',
        'success',
        'responseCode',
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


    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notificationId', 'id');
    }


    public function receiver()
    {
        return $this->hasOne(User::class, 'mobilePhone', 'phone')
            ->where('mobilePhoneCode', $this->phoneCode);
    }


    public function getSenderAttribute()
    {
        $user = ($this->notification)
            ? $this->notification->creator
            : null;
            //: User::query()->where('name', '=', '零壹管理者')->first();

        return $user;
    }


    public function getSenderNameAttribute()
    {
        return ($this->sender) ? $this->sender->name : '系統通知';
    }
}
