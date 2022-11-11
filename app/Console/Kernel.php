<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    protected function shortSchedule(\Spatie\ShortSchedule\ShortSchedule $schedule)
    {
        $schedule->command('command:syncriders')->everySeconds(5)->withoutOverlapping();
        $schedule->command('command:synchorses')->everySeconds(5)->withoutOverlapping();
        $schedule->command('command:synctrainers')->everySeconds(5)->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
