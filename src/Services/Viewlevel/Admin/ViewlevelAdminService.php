<?php

namespace DaydreamLab\User\Services\Viewlevel\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Repositories\Viewlevel\Admin\ViewlevelAdminRepository;
use DaydreamLab\User\Services\Viewlevel\ViewlevelService;
use Illuminate\Support\Collection;

class ViewlevelAdminService extends ViewlevelService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    public function __construct(ViewlevelAdminRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function addMapping($item, $input)
    {
        if (count($input->get('groupIds') ?: [])) {
            $item->groups()->attach($input->get('groupIds'));
        }
    }


    public function beforeRemove($item)
    {
        if ($item->canDelete == 0) {
            $this->throwResponse('ItemCanNotBeDeleted', ['id' => $item->id]);
        }
    }


    public function modifyMapping($item, $input)
    {
        $item->groups()->sync($input->get('groupIds'), true);
    }


    public function removeMapping($item)
    {
        $item->groups()->detach();
    }
}
