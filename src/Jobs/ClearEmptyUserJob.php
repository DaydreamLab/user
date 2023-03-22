<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Services\User\Admin\UserAdminService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use function Psy\sh;

class ClearEmptyUserJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $filePath;

    protected $rowIndex;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->queue = 'import-job';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $thirtyDayAgo = now('Asia/Taipei')->endOfDay()->subDays(16)->tz('UTC')->toDateTimeString();
        $q = new QueryCapsule();
        $q->whereRaw("mobilePhone REGEXP '^[0-9]+$'")
            ->whereNotNull('mobilePhone')
            ->whereNull('email')
            ->whereNull('name')
            ->whereNull('created_by')
            ->where('created_at', '<', $thirtyDayAgo)
            ->with('company')
            ->exec(new User())
            ->each(function ($user) {
                $user->groups()->detach();
                $user->company()->delete();
                $user->delete();
            });

//
//        $data = $users->map(function ($user) {
//            return [
//                $user->id,
//                $user->name,
//                $user->mobilePhone,
//                $user->company->name,
//                $user->email,
//                $user->company->email,
//            ];
//        });
//
//        Helper::exportXlsx(['id', '姓名', '手機','公司名稱', '電子信箱', '公司信箱'], $data, Str::random(5) . '.xlsx');
    }
}
