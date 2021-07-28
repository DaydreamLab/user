<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\Asset\Admin\AssetGroupAdmin;
use DaydreamLab\User\Repositories\Asset\Admin\AssetGroupAdminRepository;
use DaydreamLab\User\Services\Asset\AssetGroupService;
use Illuminate\Support\Collection;
use function Webmozart\Assert\Tests\StaticAnalysis\countBetween;

class AssetGroupAdminService extends AssetGroupService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    public function __construct(AssetGroupAdminRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function addMapping($item, $input)
    {
        if (count($input->get('assetIds') ?: [])) {
            $item->assets()->attach($input->get('assetIds'));
        }

        if (count($input->get('userGroupIds') ?: [])) {
            $item->usergroups()->attach($input->get('userGroupIds'));
        }
    }


    public function modifyMapping($item, $input)
    {
        $item->assets()->sync($input->get('assetIds') ?: []);
        $item->usergroups()->sync($input->get('userGroupIds') ?: []);
    }


    public function removeMapping($item)
    {
        $item->assets()->detach();
    }


    public function page(Collection $input)
    {
        $assetGroups = $this->all();
        $page = [];
        foreach ($assetGroups as $assetGroup) {
            $tempAssetGroup = $assetGroup->only(['id', 'title']);
            foreach ($assetGroup->assets as $asset) {
                $assetApis = $asset->apis->map(function ($assetApi) {
                    return [
                        'id'        => $assetApi->id,
                        'name'      => $assetApi->name,
                        'hidden'    => $assetApi->pivot->hidden,
                        'disabled'  => $assetApi->pivot->disabled,
                        'checked'   => $assetApi->pivot->checked,
                    ];
                })->values();
                $tempAsset = $asset->only(['id', 'title']);
                $tempAsset['apis'] = $assetApis;
                $tempAssetGroup['assets'][] = $tempAsset;
            }
            $page[] = $tempAssetGroup;
        }
        $this->response = $page;
        $this->status = 'getItemSuccess';
        return $this->response;
    }
}
