<?php

namespace DaydreamLab\User\Commands\Dev;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\User\User;
use Illuminate\Console\Command;

class ClearUserWithoutCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:clear-user-without-company';

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
        $this->info('清除無公司會員...');

        $users = User::whereDoesntHave('company.company')
            ->with('company', 'company.company', 'line', 'groups')
            ->get();

        $headers = [
            '身份',
            '公司名稱',
            '公司統編',
            '公司電話',
            '公司信箱',
            '公司產業別',
            '公司規模',
            '行動電話',
            '姓名',
            '公司信箱',
            '職務類別',
            '職位別',
            '採購過程中所扮演的角',
            '感興趣議題',
            '黑名單',
            '黑名單原因',
            '是否有綁定LINE',
            '來源',
            '上次登入日期'
        ];

        $data = $users->filter(function ($user) {
            return $user->groups->pluck('id')->intersect([6,7])->count();
        })->map(function ($user) {
            $userCompany = $user->company;
            $company = $userCompany->company;

            $phones = $userCompany->phones ?: [];
            $phoneStr = '';
            foreach ($phones as $key => $phone) {
                $phoneStr .= $phone['phoneCode'] . '-' . $phone['phone'];
                if ($phone['ext']) {
                    $phoneStr .= '#' . $phone['ext'];
                }
                if ($key != count($phones) - 1) {
                    $phoneStr .= ',';
                }
            }

            return [
                $user->groups->first()->title,
                ($company) ? $company->name : '',
                ($company) ? $company->vat : '',
                $phoneStr,
                ($userCompany) ? $userCompany->email : '',
                ($company) ? implode(',', $company->industry) : '',
                ($company) ? $company->scale : '',
                $user->mobilePhone ?: '',
                $user->name ?: '',
                $user->email ?: '',
                ($userCompany) ? $userCompany->department : '',
                ($userCompany) ? $userCompany->jobTitle : '',
                ($userCompany) ? $userCompany->purchaseRole : '',
                ($userCompany) ? implode(',', $userCompany->interestedIssue) : '',
                $user->block ? '是' : '否',
                $user->blockReason ?: '',
                $user->line ? '已綁訂' : '未綁訂',
                $user->activateToken == 'importedUser' ? '舊官網匯入' : ($user->activateToken == 'eventAddUser' ? '活動匯入' : '自行註冊'),
                $user->lastLoginAt ? Carbon::parse($user->lastLoginAt)->tz('Asia/Taipei')->toDateTimeString() : '無',
            ];
        })->all();

        Helper::exportXlsx($headers, $data, '無公司會員名單.xlsx');

        $this->info('清除無公司會員完成');
    }
}
