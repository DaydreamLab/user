<?php

namespace DaydreamLab\User\Listeners;

use DaydreamLab\JJAJ\Helpers\Helper;

class UserEventSubscriber
{

    protected $logService;

    public function __construct()
    {
    }


    public function onAdd($event)
    {
        if ($event->user)
        {
            $input = Helper::collect([
                'created_by' => $event->user->id,
                'action'     => $event->action,
                'result'     => gettype($event->model) == 'object' ? 'success' : 'fail',
                'type'       => $event->type,
                'item_id'    => $event->model->id ?: null,
                'payload'    => json_encode($event->input)
            ]);

            $this->logService->store($input);
        }
    }


    public function onBlock($event)
    {
        if ($event->user)
        {
            foreach ($event->item_ids as $item_id)
            {
                $input = Helper::collect([
                    'created_by' => $event->user->id,
                    'action'     => $event->action,
                    'result'     => $event->result,
                    'type'       => $event->type,
                    'item_id'    => $item_id,
                ]);

                $this->logService->store($input);
            }
        }
    }


    public function onLogin($event)
    {

        $input = Helper::collect([
            'created_by' => $event->user  ? $event->user->id : 0,
            'action'     => $event->action,
            'result'     => $event->result,
            'type'       => $event->type,
            'payload'    => json_encode($event->message)
        ]);

        $this->logService->store($input);
    }


    public function onModify($event)
    {
        if ($event->user)
        {
            $input = Helper::collect([
                'created_by' => $event->user->id,
                'action'     => $event->action,
                'result'     => $event->result ? 'success' : 'fail',
                'type'       => $event->type,
                'item_id'    => $event->model->id ?: null,
                'payload'    => json_encode($event->input)
            ]);

            $this->logService->store($input);
        }
    }


    public function onRemove($event)
    {
        if ($event->user)
        {
            foreach ($event->item_ids as $item_id)
            {
                $input = Helper::collect([
                    'created_by' => $event->user->id,
                    'action'     => $event->action,
                    'result'     => $event->result,
                    'type'       => $event->type,
                    'item_id'    => $item_id,
                ]);

                $this->logService->store($input);
            }
        }
    }


    public function subscribe($events)
    {
        $events->listen(
            'DaydreamLab\User\Events\Add',
            'DaydreamLab\User\Listeners\UserEventSubscriber@onAdd'
        );


        $events->listen(
            'DaydreamLab\User\Events\Block',
            'DaydreamLab\User\Listeners\UserEventSubscriber@onBlock'
        );


        $events->listen(
            'DaydreamLab\User\Events\Login',
            'DaydreamLab\User\Listeners\UserEventSubscriber@onLogin'
        );


        $events->listen(
            'DaydreamLab\User\Events\Modify',
            'DaydreamLab\User\Listeners\UserEventSubscriber@onModify'
        );


        $events->listen(
            'DaydreamLab\User\Events\Remove',
            'DaydreamLab\User\Listeners\UserEventSubscriber@onRemove'
        );
    }
}