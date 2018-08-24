<?php
namespace DaydreamLab\User\Models\Password\Front;

use DaydreamLab\User\Models\Password\PasswordReset;

class PasswordResetFront extends PasswordReset
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'passwords_resets';


}