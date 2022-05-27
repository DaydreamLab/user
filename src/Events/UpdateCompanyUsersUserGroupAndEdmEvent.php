<?php

namespace DaydreamLab\User\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UpdateCompanyUsersUserGroupAndEdmEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $company;

    public $companyUserGroup;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company, $companyUserGroup)
    {
        $this->company = $company;
        $this->companyUserGroup = $companyUserGroup;
    }
}
