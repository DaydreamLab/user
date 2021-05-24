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
    }


    public function modifyMapping($item, $input)
    {
        $item->assets()->sync($input->get('assetIds') ?: []);
    }



    public function beforeRemove($item)
    {
        $item->assets()->detach();
    }
}
