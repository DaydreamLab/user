<?php

namespace DaydreamLab\User\Services\UserTag\Admin;

use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\RequestHelper;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Repositories\UserTag\Admin\UserTagAdminRepository;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use DaydreamLab\User\Services\UserTag\UserTagService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
        if (!$item->botbonnieId) {
            $input->put('rules', $input->get('originalRules'));
            $userIds = $this->getCrmUserIds($input);

            if ($input->get('type') != 'auto' && $userIds->count()) {
                $userIds->chunk(1000)->each(function ($chunk) use ($item) {
                    $item->users()->attach($chunk->all());
                });
            }
        }
    }


    public function batch(Collection $input)
    {
        $result = $this->repo->batchUpdateCategoryId($input);;

        $this->status = 'BatchUpdate' . ($result ? 'Success' : 'Fail');
        $this->response = $result ? true : false;

        return $this->response;
    }

    /**
     * 強制加入會員或刪除標籤內會員（介面設計已移除...）
     * @param Collection $input
     * @return null
     * @throws \DaydreamLab\JJAJ\Exceptions\NotFoundException
     */
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

        if ($userTag->botbonnieId) {
            $users = $userTag->users;
        } else {
            $users = $this->getCrmUserIds(
                collect([
                    'rules' => $userTag->rules]),
                false,
                ['userTags', 'monthMarketingMessages']
            )
                ->merge($userTag->activeUsers)
                ->unique('id')
                ->values();
        }

        $this->status = 'SearchUsersSuccess';
        $this->response = Helper::paginate($users, $input->get('limit') ?: 15, $input->get('page') ?: 1);

        return $this->response;
    }


    public function modifyMapping($item, $input)
    {
        if (!$item->botbonnieId) {
            if ($input->get('type') != 'auto') {
                $nowUsersData = $item->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'forceAdd'  => $user->pivot->forceAdd,
                        'forceDelete'  => $user->pivot->forceDelete,
                    ];
                });

                $nowUserIds = $nowUsersData->pluck('id');
                $input->put('rules', $input->get('originalRules'));

                $newUserIds = $this->getCrmUserIds($input);
                # 找出有被強制取消或強制新增的
                $diffIds = $nowUserIds->diff($newUserIds);
                $diffUsersData = $nowUsersData->filter(function ($u) use ($diffIds) {
                    return in_array($u['id'], $diffIds->all()) && ($u['forceAdd'] || $u['forceDelete']);
                })->values();

                $newUserIds = $newUserIds->map(function ($id) {
                    return [
                        'id' => $id,
                        'forceAdd'  => 0,
                        'forceDelete'  => 0,
                    ];
                });

                foreach ($diffUsersData as $diffUserData) {
                    $newUserIds[] = collect($diffUserData)->all();
                }

                $item->users()->detach();
                $newUserIds->unique('id')->values()->chunk(1000)->each(function ($chunk) use ($item) {
                    $item->users()->attach($chunk);
                });
            }

            $item->users()->detach();
            $newUserIds->unique('id')->values()->chunk(1000)->each(function ($chunk) use ($item) {
                $sync = [];
                foreach ($chunk as $data) {
                    $index = $data['id'];
                    unset($data['id']);
                    $sync[$index] = $data;
                }
                $item->users()->sync($sync);
            });
        }
    }


    public function removeMapping($item)
    {
        $item->users()->detach();
    }


    public function search(Collection $input)
    {
        $result =  parent::search($input);

        $transformItems = $result->map(function ($tag) {
            if ($tag->botbonnieId) {
                $tag->realTimeUsers = collect();
            } else {
                $tag->realTimeUsers = $this->getCrmUserIds(
                    collect(['rules' => $tag->rules]),
                    false
                );
            }
            return $tag;
        });

        $this->response = new LengthAwarePaginator(
            $transformItems,
            $result instanceof LengthAwarePaginator ? $result->total() : $result->count(),
            $result instanceof LengthAwarePaginator ? $result->perPage() : ($input->get('limit') ?: 10),
            $result instanceof LengthAwarePaginator ? $result->currentPage() : ($input->get('page') ?: 1),
        );

        return $this->response;
    }

    /**
     * @param $input
     * @param bool $onlyIds
     * @return Collection
     */
    public function getCrmUserIds($input, bool $onlyIds = true, $withRelations = []): Collection
    {
        $q = new QueryCapsule();
        if (count($withRelations)) {
            $q->with($withRelations);
        }
        $users = $this->userAdminService->crmSearch(
            collect([
                'basic' => $input->get('rules')['basic'],
                'company' => $input->get('rules')['company'],
                'companyOrder' => $input->get('rules')['companyOrder'],
                'event' => $input->get('rules')['event'],
                'order' => $input->get('rules')['order'],
                'coupon' => $input->get('rules')['coupon'],
                'menu' => $input->get('rules')['menu'],
                'except' => $input->get('rules')['except'],
                'q' => $q,
                'paginate' => 0,
                'limit' => 0
            ])
        );

        return $onlyIds ? $users->pluck('id') : $users;
    }
}
