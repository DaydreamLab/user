<?php

namespace DaydreamLab\User\Commands\Feat\Botbonnie;

use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Helpers\BotbonnieHelper;
use DaydreamLab\User\Models\Line\Line;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\UserTag\UserTag;
use DaydreamLab\User\Services\UserTag\Admin\UserTagAdminService;
use DaydreamLab\User\Services\UserTagCategory\UserTagCategoryService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('同步 Botbonnie 標籤會員中...');

        $botbonnieUsers = BotbonnieHelper::getAllUsers();
//        Storage::disk('public')->put('users.json', json_encode($botbonnieUsers));
//        $botbonnieUsers = Helper::getJson(base_path() . '/public/users.json');

        $autoBindLineUsers = [];
        $lineBindUsers = [];
        $botbonnieLineUsers = collect($botbonnieUsers)->where('platform', 'LINE');
        foreach ($botbonnieLineUsers as $botbonnieLineUser) {
            $lineBind = Line::where('line_user_id', $botbonnieLineUser['id'])->first();

            if ($lineBind) {
                $lineBindUsers = [$lineBind->user_id];
//            if (!$lineBind && isset($botbonnieLineUser['phone'])) {
//                $user = User::where('mobilePhone', $botbonnieLineUser['phone'])->first();
//                if ($user) {0
////                    Line::create([
////                        'line_user_id' => $botbonnieLineUser->id,
////                        'user_id' => $user->id,
////                    ]);
//                    $autoBindLineUsers[] = $user->id;
//                }
            }
        }
        show($lineBindUsers);
        exit();
        $fbUsers = collect($botbonnieUsers)->where('platform', 'FB');


        $this->info('同步 Botbonnie 標籤會員完成');
        exit();
        $this->info('同步 BotBonnie 標籤中...');
        $data = getJson(__DIR__ . '/jsons/tags.json', true);
        $tagIds = collect($data)->pluck('tagId');
        UserTag::whereNotNull('botbonnieId')
            ->get()
            ->each(function ($tag) use ($tagIds) {
                if (!$tagIds->contains($tag->botbonnieId)) {
                    $tag->state = -1;
                    $tag->save();
                }
            });
        foreach ($data as $tag) {
            $tag = $this->getBotBonnieTag($tag['tagId']);
            $this->recursive($tag);
        }
        $this->info('同步 BotBonnie 完成');
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
}