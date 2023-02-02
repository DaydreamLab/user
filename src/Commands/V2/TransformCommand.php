<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\Company\CompanyCategory;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserCompany;
use DaydreamLab\User\Services\User\Front\UserFrontService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class  TransformCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:v2-transform';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install DaydreamLab user component';

    protected $constants = [];

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
        $this->call('migrate');
        $this->call('user:company-category-handle');
        $this->call('user:company-category-note-transform');
        $this->call('user:company-transform');

        $this->info('更新 UserCompany 資料中...');
        $this->transformUserCompany();
        $this->info('更新 UserCompany 資料完成');


        $this->info('更新電子報訂閱資料中...');
        $this->transformNewsletterSubscription();
        $this->info('更新電子報訂閱資料完成');

        $this->call('user:newsletter-subscription-handle');
    }


    public function transformNewsletterSubscription()
    {
        # 確保每個會員都有對應的電子報訂閱
        $users = User::with('company', 'newsletterSubscription', 'newsletterSubscription.newsletterCategories')->get();
        $counter = 0;
        foreach ($users as $user) {
            if (!$user->newsletterSubscription && $user->email) {
                $user->newsletterSubscription()->create([
                    'email' => $user->email,
                ]);
                $counter++;
            }
        }
        $this->info("有email 但是沒有電子報訂閱記錄有：{$counter}筆");
    }


    public function transformUserCompany()
    {
        # 確保每個 user 都有 userCompany
        $this->info('確保每個 user 都有 userCompany中...');
        $noUserCompanyUsers = User::whereDoesntHave('company')->get();
        $this->info("未有userCompany 的筆數： {$noUserCompanyUsers->count()}");
        $noUserCompanyUsers->each(function ($user) {
            $user->company()->create([
                'email' => trim(Str::lower($user->email))
            ]);
        });
        $this->info('確保每個 user 都有 userCompany 完成');

        $this->info('刪除沒有 user 的 userCompany中 ...');
        UserCompany::whereDoesntHave('user')->delete();
        $this->info('刪除沒有 user 的 userCompany 完成');

        $this->info('個人公司電話格式轉換中...');
        $userCompanies = UserCompany::all();
        foreach ($userCompanies as $userCompany) {
            if (!$userCompany->validateToken) {
                $userCompany->validateToken = Str::random(128);
                if ($userCompany->phone) {
                    $userCompany->phones = [
                        [
                            'phoneCode' => trim($userCompany->phoneCode),
                            'phone' => trim($userCompany->phone),
                            'ext' => $userCompany->extNumber,
                        ]
                    ];
                } else {
                    $userCompany->phones = [];
                }
                $userCompany->timestamps = false;
                $userCompany->email = trim(Str::lower($userCompany->email));
                $userCompany->save();
            }
        }
        $this->info('個人公司電話格式轉換完成');
    }
}
