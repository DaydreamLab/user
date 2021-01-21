<?php
namespace DaydreamLab\User\Models\Password;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\User\Database\Factories\PasswordResetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasswordReset extends BaseModel
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_resets';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
        'expired_at',
        'reset_at',
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


    public static function newFactory()
    {
        return PasswordResetFactory::new();
    }
}