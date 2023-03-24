<?php

namespace App\Console;

use App\Console\Commands\CleanOldPush;
use App\Console\Commands\SendNotificationApnApp;
use App\Console\Commands\SendNotificationApnClip;
use App\Console\Commands\SendNotificationFcmApp;
use App\Console\Commands\SendNotificationFcmClip;
use App\Console\Commands\UpdateApnCertificate;
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
        $schedule->command(SendNotificationFcmApp::class)->everyMinute();
        $schedule->command(SendNotificationFcmClip::class)->everyMinute();
        $schedule->command(SendNotificationApnApp::class)->everyMinute();
        $schedule->command(SendNotificationApnClip::class)->everyMinute();
        $schedule->command(CleanOldPush::class)->hourly();
        $schedule->command(UpdateApnCertificate::class)->daily();
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
