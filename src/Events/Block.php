<?php

namespace DaydreamLab\User\Events;


use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;


class Block
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $action = 'block';

    public $item_ids = [];

    public $result;

    public $user;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $result, $input, $user)
    {
        $this->user     = $user;
        $this->result   = $result ? 'success' : 'fail';
        $this->type     = $type;
        $this->item_ids = $input->get('ids');

        $block = $input->get('block');

        if ($block == 1)
        {
            $this->action = 'block';
        }
        elseif ($block == 0)
        {
            $this->action = 'unblock';
        }
    }

}
