<?php

namespace DaydreamLab\User\Database\Seeds;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Asset\AssetApi;
use DaydreamLab\User\Repositories\Asset\AssetRepository;
use DaydreamLab\User\Services\Asset\AssetService;
use Illuminate\Database\Seeder;

class AssetsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__.'/jsons/asset.json'), true);

        $this->migrate($data, null);

        $service    = new AssetService(new AssetRepository(new Asset()));

        $combine_path = function ($parent_id, $full_path) use (&$combine_path, $service) {
            if($parent_id == 1) {
                return $full_path;
            }
            else {
                $parent = $service->find($parent_id);
                $full_path = $parent->path . $full_path;
                return $combine_path($parent->parent_id, $full_path);
            }
        };

        $assets     = $service->search(Helper::collect(['limit' => 10000]));
        $assets->forget('pagination');
        foreach ($assets as $asset) {
            $full_path = $asset->path;
            $asset->full_path = $combine_path($asset->parent_id, $full_path);
            $asset->save();
        }
    }

    public function migrate($data, $parent)
    {
        foreach ($data as $item)
        {
            $apis       = $item['apis'];
            $children   = $item['children'];
            unset($item['children']);
            unset($item['apis']);

            $asset = Asset::create($item);
            if ($parent)
            {
                $parent->appendNode($asset);
            }

            foreach ($apis as $api)
            {
                $api['asset_id'] = $asset->id;
                $asset_api = AssetApi::create($api);
            }

            if (count($children))
            {
                self::migrate($children, $asset);
            }
        }

    }
}
