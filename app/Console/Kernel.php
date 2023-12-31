<?php

namespace App\Console;

use App\Console\Commands\SendReminders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // protected function schedule(Schedule $schedule): void
    // {
    //     $schedule->command('backup:run')->everyMinute();
    // }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

        protected function schedule(Schedule $schedule)
    {
        $schedule->command('students:delete-inactive')->daily();
    }



protected $commands = [
    // ...
    \App\Console\Commands\DeleteInactiveStudents::class,
];

//     protected function schedule(Schedule $schedule)
// {
//     $schedule->command(SendReminders::class)->everyMinute();
// }

}
