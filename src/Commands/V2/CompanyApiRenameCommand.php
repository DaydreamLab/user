<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\Company\Company;
use Illuminate\Console\Command;

class CompanyApiRenameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:company-api-rename';

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
        $this->info('更改公司相關 API 名稱');

        $apis = Api::whereBetween('id', [277,  282])->get();
        foreach ($apis as $api) {
            if (strpos($api->name, '統編') !== false) {
                $api->name = str_replace('統編', '公司', $api->name);
                $api->save();
            }
        }

        $this->info('更改公司相關 API 名稱完成');
    }
}
