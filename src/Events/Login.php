<?php

namespace DaydreamLab\User\Events;


use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;


class Login
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $action = 'login';

    public $message;

    public $result;

    public $user;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $result, $message, $user)
    {
        $this->user     = $user;
        $this->type     = $type;
        $this->message  = $message;
        $this->result   = $result ? 'success' : 'fail';
    }

}
