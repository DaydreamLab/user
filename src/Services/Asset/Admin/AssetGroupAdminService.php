<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Asset\Admin\AssetGroupAdminRepository;
use DaydreamLab\User\Services\Asset\AssetGroupMapService;
use DaydreamLab\User\Services\Asset\AssetGroupService;
use Illuminate\Support\Collection;

class AssetGroupAdminService extends AssetGroupService
{
    protected $type = 'AssetGroupAdmin';

    protected $assetGroupMapService;

    public function __construct(AssetGroupAdminRepository $repo,
                                AssetGroupMapService $assetGroupMapService)
    {
        parent::__construct($repo);
        $this->repo = $repo;
        $this->assetGroupMapService = $assetGroupMapService;
    }


    public function store(Collection $input, $diff = false)
    {
        $item = parent::store($input, $diff);

        if (!gettype($item) == 'object')
        {
            $item = $this->find($input->get('id'));
        }

        $result = $this->assetGroupMapService->storeKeysMap(Helper::collect([
            'group_id'      => $item->id,
            'asset_ids'     => $input->asset_ids
        ]));

        return $item;
    }
}
