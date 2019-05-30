<?php

namespace DaydreamLab\User\Services\Asset;

use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Traits\NestedServiceTrait;
use DaydreamLab\User\Repositories\Asset\AssetRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class AssetService extends BaseService
{
    use NestedServiceTrait{
        NestedServiceTrait::addNested       as traitAddNested;
        NestedServiceTrait::modifyNested    as traitModifiedNested;
        NestedServiceTrait::storeNested     as traitStoreNested;
        NestedServiceTrait::removeNested    as traitRemoveNested;
    }

    protected $type = 'Asset';

    public function __construct(AssetRepository $repo)
    {
        parent::__construct($repo);
    }


    public function getApis($id)
    {
        $asset = $this->find($id);
        $this->status =  Str::upper(Str::snake($this->type.'GetApisSuccess'));;
        $this->response = $asset->apis;

        return $asset->apis;
    }


    public function getGroups($id)
    {
        $asset = $this->find($id);
        $this->status =  Str::upper(Str::snake($this->type.'GetGroupsSuccess'));;
        $this->response = $asset->groups;

        return $asset->groups;
    }


    public function remove(Collection $input ,$diff = false)
    {
        $result = $this->traitRemoveNested($input, $diff);

        return $result;
    }


    public function store(Collection $input, $diff = false)
    {
        // 計算full path
        if (!InputHelper::null($input, 'parent_id')) {
            $parent_id  = $input->parent_id;
            $parent     = $this->find($parent_id);
            $full_path  = $parent->full_path . $input->path;
        }
        else {
            $full_path  = $input->path;
        }

        $input->put('full_path', $full_path);

        return $this->storeNested($input);
    }
}
