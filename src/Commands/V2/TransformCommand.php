<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\Company\CompanyCategory;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserCompany;
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
    }


    public function transformNewsletterSubscription()
    {
        $users = User::with('newsletterSubscription')->get();
        foreach ($users as $user) {
            if (!$user->newsletterSubscription) {
                $user->newsletterSubscription()->create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }
        }
    }


    public function transformUserCompany()
    {
        $userCompanies = UserCompany::all();
        foreach ($userCompanies as $userCompany) {
            if (!$userCompany->validateToken) {
                $userCompany->validateToken = Str::random(128);
                if ($userCompany->phone) {
                    $userCompany->phones = [
                        [
                            'phoneCode' => $userCompany->phoneCode,
                            'phone' => $userCompany->phone,
                            'ext' => $userCompany->extNumber,
                        ]
                    ];
                } else {
                    $userCompany->phones = [];
                }
                $userCompany->save();
            }
        }
    }
}
