<?php

namespace App\Console;

use App\Console\Commands\CleanOldPushCommand;
use App\Console\Commands\SendNotificationApnAppCommand;
use App\Console\Commands\SendNotificationApnClipCommand;
use App\Console\Commands\SendNotificationFcmAppCommand;
use App\Console\Commands\SendNotificationFcmClipCommand;
use App\Console\Commands\UpdateApnCertificateCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
//        $schedule->command(SendNotificationFcmApp::class)->everyMinute();
//        $schedule->command(SendNotificationFcmClip::class)->everyMinute();
//        $schedule->command(SendNotificationApnApp::class)->everyMinute();
//        $schedule->command(SendNotificationApnClip::class)->everyMinute();
        $schedule->command(CleanOldPushCommand::class)->hourly();
        $schedule->command(UpdateApnCertificateCommand::class)->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
