<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\JJAJ\Exceptions\NotFoundException;
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\Company\CompanyCategory;
use DaydreamLab\User\Models\User\UserCompany;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HandleUserValidateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:validate-handle';

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
        $this->info('更新會員自動驗證中...');

        $spreedsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreedsheet->getActiveSheet();
        $headers = ['id', '姓名', '行動電話', '公司統編', '公司名稱', '公司Domain', '公司信箱', '上次登入時間', '是否有回娘家', '參加過的活動數'];
        $spreedsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreedsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreedsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreedsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreedsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreedsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreedsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreedsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreedsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreedsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $h = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($h, 1, $header);
            $h += 1;
        }

        $original = DB::table('users_groups_maps')
            ->where('group_id', 6)
            ->get();

        $counter = 0;
        $r = 2;
        UserCompany::whereIn('user_id', function ($q) {
            $q->select('user_id')
                ->from('users_groups_maps')
                ->where('group_id', 6);
        })
        ->with(['user', 'user.company', 'user.company.company', 'user.orders', 'user.company.company.category'])
        ->chunkById(1000, function ($userCompanies) use (&$counter, &$r, $headers, &$spreedsheet, &$sheet) {
            $userCompanies->each(function ($userCompany) use (&$counter, &$r, $headers, &$spreedsheet, &$sheet) {
                $user = $userCompany->user;
                if ($user->isDealer && $user->companyEmailIsDealer) {
                    $userCompany->validated = 1;
                    $userCompany->lastValidate = now()->toDateTimeString();
                    $userCompany->timestamps = false;
                    $userCompany->save();
                    $counter++;
                } else {
                    for ($i = 1; $i <= count($headers); $i += 1) {
                        switch ($i) {
                            case 1:
                                $v = $user->id;
                                break;
                            case 2:
                                $v = $user->name;
                                break;
                            case 3:
                                $v = $user->mobilePhone;
                                break;
                            case 4:
                                $v = $user->company->company ? $user->company->company->vat : $user->company->vat;
                                break;
                            case 5:
                                $v = $user->company->company ? $user->company->company->name : $user->company->name;
                                break;
                            case 6:
                                $v = $user->company->company ? $user->company->company->domain : null;
                                break;
                            case 7:
                                $v = $userCompany->email;
                                break;
                            case 8:
                                $v = $user->lastLoginAt;
                                break;
                            case 9:
                                $v = $user->backHome ? '是' : '否';
                                break;
                            case 10:
                                $v = $user->orders->count();
                                break;
                            default:
                                $v = '';
                                break;
                        }
                        # 電話號碼也當成字串硬存
                        $sheet->setCellValueExplicitByColumnAndRow($i, $r, $v, 's');
                    }
                    $r++;
                }
            });
        });

        $filename = 'auto-vildate-failed.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreedsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
        $writer->save(public_path() . $filename);

        $str =  '原始經銷會員人數：' . $original->count() . ' 自動驗證成功人數：' . $counter
            . ' 無法自動驗證人數：' . ($original->count() - $counter);
        $this->info($str);
        $this->info('更新會員自動驗證完成');
    }
}
