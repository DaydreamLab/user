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

//        $botbonnieUsers = BotbonnieHelper::getAllUsers();
//        Storage::disk('public')->put('tags.json', json_encode(BotbonnieHelper::getTags($botbonnieUsers)));
//        Storage::disk('public')->put('users.json', json_encode($botbonnieUsers));

        $botbonieTags = Helper::getJson(base_path() . '/public/tags.json');
        $botbonnieUsers = Helper::getJson(base_path() . '/public/users.json');
        $lineBinds = Line::whereIn('line_user_id', collect($botbonnieUsers)->pluck('id')->all())
            ->with('users')->get();
        $autoBindLineUsers = [];
        $lineBindUsers = [];
        $botbonnieLineUsers = collect($botbonnieUsers)->where('platform', 'LINE');
        foreach ($botbonnieLineUsers as $botbonnieLineUser) {
            $lineBind = $lineBinds->where('line_user_id', $botbonnieLineUser['id'])->first();
            if ($lineBind) {
                $lineBindUsers[] = $lineBind;
            } else {
                if (!$lineBind && isset($botbonnieLineUser['phone'])) {
                    $user = User::where('mobilePhone', $botbonnieLineUser['phone'])->first();
                    if ($user) {
//                        Line::create([
//                            'line_user_id' => $botbonnieLineUser->id,
//                            'user_id' => $user->id,
//                        ]);
                        $autoBindLineUsers[] = $user->id;
                    }
                }
            }
        }

        # 處理會員的標籤同步問題
        foreach ($lineBindUsers as $lineBindUser) {
            $user = $lineBindUser->users->first();
            show($lineBindUser);
            exit();
        }

        foreach ($botbonieTags as $botbonieTag) {
            $tagUsers = $lineBindUsers->filter(function ($bindUser) use ($botbonieTag) {
                return collect($botbonieTag['users'])->pluck('id')->contains($bindUser->line_user_id);
            })->values();
            show($botbonieTag);
            exit();
        }
//show($lineBindUsers);


        $fbUsers = collect($botbonnieUsers)->where('platform', 'FB');
exit();
        $this->info('同步 Botbonnie 標籤會員完成');
        exit();
    }

}