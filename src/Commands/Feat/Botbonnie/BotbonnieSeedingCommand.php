<?php

namespace DaydreamLab\User\Commands\Feat\Botbonnie;

use DaydreamLab\Cms\Models\Category\Category;
use DaydreamLab\Cms\Services\Category\Admin\CategoryAdminService;
use DaydreamLab\Cms\Services\IotCategory\Admin\IotCategoryAdminService;
use DaydreamLab\Cms\Services\Site\Admin\SiteAdminService;
use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Exceptions\NotFoundException;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Services\Asset\Admin\AssetAdminService;
use DaydreamLab\User\Services\Asset\Admin\AssetGroupAdminService;
use DaydreamLab\User\Services\UserTagCategory\UserTagCategoryService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BotbonnieSeedingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:botbonnie-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'seeding solution data';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function getBotBonnieTag($tagId)
    {
        $response = (new Client())->get(
            'https://api.botbonnie.com/v1/api/tag/' . $tagId,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('app.botbonnie_token'),
                    'Content-Type' => 'application/json'
                ]
            ]
        );

        return json_decode($response->getBody()->getContents())->res;
    }


    public function findOrCreate($tag)
    {

    }

    public function recursive($tag)
    {
        $service = app(UserTagCategoryService::class);
        if (property_exists($tag, 'parentId')) {
            $parentTag = $service->search(collect([
                'q' => (new QueryCapsule())->where('title', $tag->name)
                    ->whereJsonContains('params', ['BotBonnieId' => $tag->parentId])]))->first();
            if (!$parentTag) {
                return $this->recursive($this->getBotBonnieTag($tag->parentId));
            }
        }

        $tagCategory = $service->search(collect([
            'q' => (new QueryCapsule())->where('title', $tag->name)
                ->whereJsonContains('params', ['BotBonnieId' => $tag->id])]))->first();
        if (!$tagCategory) {
            $tagCategory = $service->store(collect([
                'title' => $tag->name,
                'parent_id' => isset($parentCategory) ? $parentCategory->id : null,
                'params' => [
                    'BotBonnieId' => $tag->id,
                    'BotBonnieParentId' => property_exists($tag, 'parentId') ? $tag->parentId : null
                ]
            ]));
        }
        return true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = getJson(__DIR__ . '/jsons/tags.json', true);
        foreach ($data as $tag) {
            $tag = $this->getBotBonnieTag($tag['tagId']);
            $this->recursive($tag);
        }
    }
}
