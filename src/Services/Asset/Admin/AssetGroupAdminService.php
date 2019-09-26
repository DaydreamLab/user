<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Asset\Admin\AssetGroupAdmin;
use DaydreamLab\User\Repositories\Asset\Admin\AssetGroupAdminRepository;
use DaydreamLab\User\Services\Asset\AssetGroupService;
use Illuminate\Support\Collection;

class AssetGroupAdminService extends AssetGroupService
{
    protected $type = 'AssetGroupAdmin';


    public function __construct(AssetGroupAdminRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }

//    /**
//     * @param $item AssetGroupAdmin
//     * @param $input Collection
//     * @return bool|void
//     */
//    public function addMapping($item, $input)
//    {
//        $item->assets()->attach($input->get('asset_ids'));
//    }
//
//
//    public function modifyMapping($item, $input)
//    {
//        $item->assets()->detach();
//        $item->assets()->attach($input->get('asset_ids'));
//    }
//
//
//    public function removeMapping($item)
//    {
//        $item->assets()->detach();
//    }


    public function store(Collection $input, $diff = false)
    {
        $item = parent::store($input, $diff);

        if (!gettype($item) == 'object')
        {
            $item = $this->find($input->get('id'));
        }

        return $item;
    }
}
