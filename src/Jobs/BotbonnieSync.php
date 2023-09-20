<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Helpers\BotbonnieHelper;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\Line\Line;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\UserTag\UserTag;
use DaydreamLab\User\Services\User\Front\UserFrontService;
use DaydreamLab\User\Services\UserTag\Admin\UserTagAdminService;
use DaydreamLab\User\Services\UserTagCategory\UserTagCategoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class BotbonnieSync implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

//        $this->onQueue('');
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        # 處理標籤同步
        $botbonnieUsers = BotbonnieHelper::getAllUsers();
        $botbonnieTags = BotbonnieHelper::getTags($botbonnieUsers);
        foreach ($botbonnieTags as $botbonnieTag) {
            $this->recursiveVisit($botbonnieTag);
        }


//        Storage::disk('public')->put('tags.json', json_encode(BotbonnieHelper::getTags($botbonnieUsers)));
//        Storage::disk('public')->put('users.json', json_encode($botbonnieUsers));
//        $botbonieTags = Helper::getJson(base_path() . '/public/tags.json');
//        $botbonnieUsers = Helper::getJson(base_path() . '/public/users.json', false);
        $users = $this->handleLineUsers($botbonnieUsers);
        foreach ($users as $user) {
            $userTags = UserTag::whereIn('botbonnieId', collect($user->tags)->pluck('id')->all())->get();
            if (!$user->user) {
                continue;
            }
            $user->user->userTags()->syncWithoutDetaching($userTags->pluck('id')->all());
        }
    }


    public function getBotBonnieChannelUsers($channel, $botbonnieUsers): Collection
    {
        return collect($botbonnieUsers)->where('platform', $channel);
    }


    public function handleLineUsers(array $botbonnieUsers): Collection
    {
        $lineBindUsers = [];
        $lineBinds = Line::whereIn('line_user_id', collect($botbonnieUsers)->pluck('id')->all())
            ->with('users')->get();
        foreach ($botbonnieUsers as $botbonnieUser) {
            $lineBind = $lineBinds->where('line_user_id', $botbonnieUser->id)->first();
            if ($lineBind) {
                $lineBindUsers[] = $lineBind;
            } else {
                if (!$lineBind && isset($botbonnieLineUser->phone)) {
                    $user = User::where('mobilePhone', $botbonnieLineUser->phone)->first();
                    if ($user) {
                        $lineBind = Line::create([
                            'line_user_id' => $botbonnieLineUser->id,
                            'user_id' => $user->id,
                        ]);
                        $lineBindUsers[] = $lineBind;
                    }
                }
            }
        }

        return collect($botbonnieUsers)->whereIn('id', collect($lineBindUsers)->pluck('line_user_id')->all())
            ->map(function ($botbonnieUser) use ($lineBindUsers) {
                $botbonnieUser->user = collect($lineBindUsers)
                    ->where('line_user_id', $botbonnieUser->id)
                    ->first()
                    ->users
                    ->first();
                return $botbonnieUser;
            });
    }



    public function recursiveVisit($tag)
    {
        if (!property_exists($tag, 'type')) {
            return false;
        } elseif ($tag->type == 0) {
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
                    $userTagCategory = $this->recursiveVisit(BotbonnieHelper::getTag($tag->parentId));
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
                   'rules' => EnumHelper::DEFFAULT_CRM_RULES
                ]));
            } else {
                $userTag->title = $tag->name;
                unset($userTag->realTimeUsers);
                $userTag->save();
            }
        } else {
            if (property_exists($tag, 'parentId')) {
                $parentTagCategory = $this->recursiveVisit(BotbonnieHelper::getTag($tag->parentId));
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
