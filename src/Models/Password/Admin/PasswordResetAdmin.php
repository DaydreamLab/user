<?php
namespace App\Models\Password\Admin;

use App\Models\Password\PasswordReset;

class PasswordResetAdmin extends PasswordReset
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'passwords_resets';


}