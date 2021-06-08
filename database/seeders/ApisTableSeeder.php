<?php

namespace DaydreamLab\User\Database\Seeders;

use DaydreamLab\User\Models\Api\Api;
use Illuminate\Database\Seeder;

class ApisTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = getJson(__DIR__ . '/jsons/api.json', true);
        $counter = Api::all()->count();
        foreach ($data as $apiData) {
            $apiData['ordering'] = ++$counter;
            Api::create($apiData);
        }
    }
}
