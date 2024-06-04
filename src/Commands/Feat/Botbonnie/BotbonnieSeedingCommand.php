<?php

namespace DaydreamLab\User\Commands\Feat\Botbonnie;

use DaydreamLab\User\Models\BotbonnieBind\BotbonnieBind;
use DaydreamLab\User\Models\Line\Line;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $lines = Line::all();
        $data = [];
        $lines->each(function ($line) use (&$data) {
            $data[] = [
                'platform' => 'LINE',
                'page_id' => config('app.botbonnie_line_page_id'),
                'botbonnie_user_id' => $line->line_user_id,
                'user_id' => $line->user_id,
                'created_at' => now()->toDateTimeString()
            ];
        });
        DB::table('botbonnie_binds')->insert($data);
    }
}
