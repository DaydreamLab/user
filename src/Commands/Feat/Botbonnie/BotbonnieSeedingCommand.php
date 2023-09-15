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
use DaydreamLab\User\Services\UserTag\Admin\UserTagAdminService;
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


    public function recursive($tag)
    {
        if ($tag->type == 0) {
            # 找出這個標籤的分類存不存在
            if (!property_exists($tag, 'parentId')) {
                $userTagCategory = app(UserTagCategoryService::class)
                    ->search(collect([
                        'alias' => 'uncategory'
                    ]))->first();
            } else {
                $userTagCategory = app(UserTagCategoryService::class)
                    ->search(collect([
                        'q' => (new QueryCapsule())
                            ->whereJsonContains('params', ['BotBonnieId' => $tag->parentId, 'BotId' => $tag->botId])
                    ]))->first();
                if (!$userTagCategory) {
                    $userTagCategory = $this->recursive($this->getBotBonnieTag($tag->parentId));
                }
            }
            $userTag =  app(UserTagAdminService::class)->search(collect([
                'q' => (new QueryCapsule())->where('botbonnieId', $tag->id)->where('botId', $tag->botId)
            ]))->first();
            if (!$userTag) {
                $userTag = app(UserTagAdminService::class)->store(collect([
                    'title' => $tag->name,
                    'categoryId' => $userTagCategory ? $userTagCategory->id : null,
                    'botbonnieId' => $tag->id,
                    'botId' => $tag->botId,
                    'type'  => 'manual',
                    'rules' => []
                ]));
            } else {
                $userTag->title = $tag->name;
                unset($userTag->realTimeUsers);
                $userTag->save();
            }
        } else {
            if (property_exists($tag, 'parentId')) {
                $parentTagCategory = $this->recursive($this->getBotBonnieTag($tag->parentId));
            }
            $tagCategory = app(UserTagCategoryService::class)
                ->search(collect([
                    'q' => (new QueryCapsule())
                        ->whereJsonContains('params', ['BotBonnieId' => $tag->id, 'BotId' => $tag->botId])
                ]))->first();
            if (!$tagCategory) {
                $tagCategory = app(UserTagCategoryService::class)->store(collect([
                    'title' => $tag->name,
                    'parent_id' => isset($parentTagCategory) ? $parentTagCategory->id : null,
                    'params' => [
                        'BotBonnieId' => $tag->id,
                        'BotId' => $tag->botId
                    ]
                ]));
            } else {
                $tagCategory->title = $tag->name;
                $tagCategory->parent_id = isset($parentTagCategory) ? $parentTagCategory->id : $tagCategory->parent_id;
                $tagCategory->save();
            }
            return $tagCategory;
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
        #todo 還要處理被刪除的部分
        foreach ($data as $tag) {
            $tag = $this->getBotBonnieTag($tag['tagId']);
            $this->recursive($tag);

        }

    }
}
