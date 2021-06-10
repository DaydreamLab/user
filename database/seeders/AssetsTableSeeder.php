<?php

namespace DaydreamLab\User\Database\Seeders;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\Asset\Asset;
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
//        $data = Helper::getJson(__DIR__ . '/jsons/asset.json', true);
//
//        $this->migrate($data, null);
//
//        $service    = app(AssetService::class);
//
//        $combine_path = function ($parent_id, $full_path) use (&$combine_path, $service) {
//            if (!$parent_id || $parent_id == 1) {
//                return $full_path;
//            } else {
//                $parent = $service->find($parent_id);
//                $full_path = $parent
//                    ? $parent->path . $full_path
//                    : $full_path;
//                return $combine_path($parent->parent_id, $full_path);
//            }
//        };
//
//        $assets     = $service->findBy('id' , '!=', 1);
//        $assets->forget('pagination');
//
//        foreach ($assets as $asset) {
//            $full_path = $asset->path;
//            $asset->full_path = $combine_path($asset->parent_id, $full_path);
//            $asset->save();
//        }
    }

    public function migrate($data, $parent)
    {
        foreach ($data as $item)
        {
            $apis       = $item['apis'];
            $children   = $item['children'];
            $service    = isset($item['service']) ? $item['service'] : null;
            unset($item['children']);
            unset($item['apis']);
            unset($item['service']);

            if ($parent) {
                $parent = Asset::find($parent->id);
                $item['ordering'] = $parent->children->count()+1;
            }

            $asset = Asset::create($item);
            if ($parent) {
                $parent->appendNode($asset);
            }

//            $api_ids = [];
//            foreach ($apis as $api) {
//                $api = Api::create($api);
//                $api_ids[] = $api->id;
//            }
//            $asset->apis()->attach($api_ids);

            if (count($children)) {
                self::migrate($children, $asset);
            }
        }
    }
}
