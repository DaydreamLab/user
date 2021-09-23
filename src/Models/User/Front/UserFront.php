<?php
namespace DaydreamLab\User\Models\User\Front;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\User\User;

class UserFront extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';


    protected $hidden = [
        'id',
    ];

    static $custom_relations = [];


    public static function boot()
    {
        parent::boot();

        static::created(function($item){
            $item->created_by = $item->id;
            $item->save();
        });
    }
}