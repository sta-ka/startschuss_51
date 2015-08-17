<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\AdminCreateCommand::class,

        \App\Console\Commands\ImportCompaniesCommand::class,
        \App\Console\Commands\ImportEventsCommand::class,

        \App\Console\Commands\UserLoginsCommand::class,
        \App\Console\Commands\UserShowCommand::class,
        \App\Console\Commands\UserSuspendCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
    }
}
