<?php

namespace DaydreamLab\User\Models\User;

use Illuminate\Database\Eloquent\Model;


class OauthAccessToken extends Model
{

    protected $table = 'oauth_access_tokens';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expires_at'
    ];

}
