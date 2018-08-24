<?php
namespace DaydreamLab\User\Models\Password\Admin;

use DaydreamLab\User\Models\Password\PasswordReset;

class PasswordResetAdmin extends PasswordReset
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'passwords_resets';


}