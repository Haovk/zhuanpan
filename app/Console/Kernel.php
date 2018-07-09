<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->command('inspire')->everyTenMinutes();

        //$schedule->command('route:list')->dailyAt('02:00');
        //$schedule->command('resetturntableuser')->dailyAt('00:00');
        $schedule->call(function(){
            $dbh = DB::connection()->getPdo();            
            $stmt = $dbh->prepare("call resetturntableuser()");
            $r = $stmt->execute();
        })->dailyAt('00:00')->description('重置转盘抽奖次数');
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
