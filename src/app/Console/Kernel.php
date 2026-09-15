<?php

namespace App\Console;

use App\Console\Commands\JobPoint\SyncMailWithDatabaseCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SyncMailWithDatabaseCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sync:mail')->everyMinute();
        $schedule->command('job:alert')->everyMinute();
        $schedule->command('sitemap:generate')->daily();

        $schedule->command('queue:work --queue=high,default --tries=2 --stop-when-empty')
            ->everyMinute()
            ->withoutOverlapping();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
