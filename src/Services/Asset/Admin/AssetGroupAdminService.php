<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\Asset\Admin\AssetGroupAdmin;
use DaydreamLab\User\Repositories\Asset\Admin\AssetGroupAdminRepository;
use DaydreamLab\User\Services\Asset\AssetGroupService;
use Illuminate\Support\Collection;

class AssetGroupAdminService extends AssetGroupService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    public function __construct(AssetGroupAdminRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function store(Collection $input)
    {
        $item = parent::store($input);

        if (!gettype($item) == 'object') {
            $item = $this->find($input->get('id'));
        }

        return $item;
    }
}
