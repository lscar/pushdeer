<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PushDeerMessage;
use Carbon\Carbon;

class CleanOldPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanpush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除过时的推送内容';

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
     * @return int
     */
    public function handle(): int
    {
        $items_count = 10000; // 每次最多删除条数
        $days = intval(config('services.limit.message.cache'));
        if ($days > 0) {
            PushDeerMessage::whereDate('created_at', '<', Carbon::now()->subDays($days))
                ->take($items_count)
                ->delete();
        }

        return 0;
    }
}
