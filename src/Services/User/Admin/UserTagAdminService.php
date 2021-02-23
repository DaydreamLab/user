<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Repositories\User\Admin\UserTagAdminRepository;
use DaydreamLab\User\Services\User\UserTagService;
use Illuminate\Support\Collection;

class UserTagAdminService extends UserTagService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    protected $userAdminService;

    public function __construct(
        UserTagAdminRepository $repo,
        UserAdminService $userAdminService)
    {
        parent::__construct($repo);
        $this->repo = $repo;
        $this->userAdminService = $userAdminService;
    }


    public function apply(Collection $input)
    {
        $addTags    = $this->search($input->get('getAddQuery'));
        if ($addTags->count() != count($input->get('addIds'))) {
            $this->throwResponse('InvalidApplyAddIds');
        }
        $deleteTags = $this->search($input->get('getDeleteQuery'));
        if ($deleteTags->count() != count($input->get('deleteIds'))) {
            $this->throwResponse('InvalidApplyDeleteIds');
        }

        $users = $this->userAdminService->findBySpecial('whereIn', 'id', $input->get('userIds'));

        foreach ($users as $user) {
            $user->tags()->sync($input->get('addIds'));
            $user->tags()->detach($input->get('deleteIds'));
        }

        $this->status =  'ApplyTagsSuccess';
        $this->response = null;

        return $this->response;
    }


    public function removeMapping($item)
    {
        $item->users()->detach();
    }
}
