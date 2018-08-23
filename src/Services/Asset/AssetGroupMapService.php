<?php

namespace DaydreamLab\User\Services\Asset;

use DaydreamLab\User\Repositories\Asset\AssetGroupMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class AssetGroupMapService extends BaseService
{
    protected $type = 'AssetGroupMap';

    public function __construct(AssetGroupMapRepository $repo)
    {
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        $maps = $this->findBy('asset_id', '=', $input->asset_id);
        foreach ($maps as $map) {
            $map->delete();
        }

        foreach ($input->group_ids as $group_id) {
            $this->create([
                'asset_id'  => $input->asset_id,
                'group_id' => $group_id
            ]);
        }

        $this->status =  Str::upper(Str::snake($this->type.'StoreSuccess'));;
        return true;
    }
}
