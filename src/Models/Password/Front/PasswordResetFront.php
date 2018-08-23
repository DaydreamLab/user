<?php
namespace App\Models\Password\Front;

use App\Models\Password\PasswordReset;

class PasswordResetFront extends PasswordReset
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'passwords_resets';


}