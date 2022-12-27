<?php

namespace DaydreamLab\User\Services\UserTag\Admin;

use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\UserTag\Admin\UserTagAdminRepository;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use DaydreamLab\User\Services\UserTag\UserTagService;
use Illuminate\Support\Collection;

class UserTagAdminService extends UserTagService
{
    protected $userAdminService;

    public function __construct(UserTagAdminRepository $repo, UserAdminService $userAdminService)
    {
        parent::__construct($repo);
        $this->repo = $repo;
        $this->userAdminService = $userAdminService;
    }


    public function addMapping($item, $input)
    {
        $userIds = $this->getCrmUserIds($input);

        if ($input->get('type') != 'auto' && count($userIds)) {
            $item->users()->attach($userIds);
        }
    }


    public function editUsers(Collection $input)
    {
        $userTag = $this->checkItem($input);

        $addIds = $input->get('addIds') ?: [];
        if (count($addIds)) {
            foreach ($addIds as $addId) {
                if ($userTag->users()->allRelatedIds()->contains($addId)) {
                    $userTag->users()->updateExistingPivot($addId, ['forceAdd' => 1, 'forceDelete' => 0]);
                } else {
                    $userTag->users()->attach($addId, ['forceAdd' => 1]);
                }
            }
        }

        $deleteIds = $input->get('deleteIds') ?: [];
        if (count($deleteIds)) {
            foreach ($deleteIds as $deleteId) {
                if ($userTag->users()->allRelatedIds()->contains($deleteId)) {
                    $userTag->users()->updateExistingPivot($deleteId, ['forceDelete' => 1, 'forceAdd' => 0]);
                } else {
                    $userTag->users()->attach($deleteId, ['forceDelete' => 1]);
                }
            }
        }

        $this->status = 'EditUsersSuccess';

        return $this->response;
    }


    public function getUsers(Collection $input)
    {
        $userTag = $this->checkItem($input);

        if ($search = $input->get('search')) {
            $users = $userTag->activeUsers()->where(function ($q) use ($search) {
                $q->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('users.mobilePhone', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('users_companies.email', 'like', "%{$search}%")
                            ->orWhereHas('company', function ($q) use ($search) {
                                $q->where('companies.name', 'like', "%{$search}%");
                            });
                    });
            })->get();
        } else {
            $users = $userTag->activeUsers;
        }

        $this->status = 'SearchUsersSuccess';
        $this->response = Helper::paginate($users, $input->get('limit') ?: 15, $input->get('page') ?: 1);

        return $this->response;
    }



    public function modifyMapping($item, $input)
    {
        if ($input->get('type') != 'auto') {
            $nowUsersData = $item->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'forceAdd'  => $user->pivot->forceAdd,
                    'forceDelete'  => $user->pivot->forceDelete,
                ];
            });
            $nowUserIds = $nowUsersData->pluck('id');
            $newUserIds = $this->getCrmUserIds($input);

            # 找出有被強制取消或強制新增的
            $diffIds = $nowUserIds->diff($newUserIds);
            $diffUsersData = $nowUsersData->filter(function ($u) use ($diffIds) {
                return in_array($u['id'], $diffIds->all()) && ($u['forceAdd'] || $u['forceDelete']);
            });
            foreach ($diffUsersData as $diffUserData) {
                $newUserIds[$diffUserData['id']] = collect($diffUserData)->except('id')->all();
            }

            $item->users()->sync($newUserIds);
        }
    }


    public function removeMapping($item)
    {
        $item->users()->detach();
    }


    /**
     * @param $input
     * @return Collection
     */
    public function getCrmUserIds($input): Collection
    {
        $q = new QueryCapsule();
        $users = $this->userAdminService->crmSearch(
            collect([
                'basic' => $input->get('rules')['basic'],
                'company' => $input->get('rules')['company'],
                'event' => $input->get('rules')['event'],
                'order' => $input->get('rules')['order'],
                'coupon' => $input->get('rules')['coupon'],
                'q' => $q,
                'paginate' => 0,
                'limit' => 0
            ])
        );

        $userIds = $users->pluck('id');

        return $userIds;
    }
}
