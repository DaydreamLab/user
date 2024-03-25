<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\Line\Line;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BotbonnieCrmIdSync implements ShouldQueue
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
    public function __construct(protected array $ids)
    {
        $this->onQueue('import-job');
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        $lines = Line::whereIn('id', $this->ids)
            ->with('users')
            ->get()
            ->each(function ($line) {
                $line->users->each(function ($user) use ($line) {
                    $response = (new Client())->post(
                        'https://api.botbonnie.com/v2/customer/accountLink/bind',
                        $data = [
                            'headers' => [
                                'Authorization' => 'Bearer ' . config('app.botbonnie_token'),
                                'Content-Type' => 'application/json'
                            ],
                            'json' => [
                                'platform' => 1,
                                'pageId' => config('app.botbonnie_line_page_id'),
                                'userId' => $line->line_user_id,
                                'crmId' => $user->uuid
                            ]
                        ]
                    );
                    Log::info($response->getBody()->getContents());
                });
            });
    }
}
