<?php

namespace App\Console;

use App\Http\Controllers\ChargeController;
use App\Http\Controllers\CsvDataController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\ChargeService;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Every minute only for challenge delivery  purposes
        $schedule->call('App\Http\Controllers\CsvDataController@createChargeFromCSVDatabase')->everyMinute(); 
        $schedule->call('App\Http\Controllers\ChargeController@sendChargeToCustomers')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        include base_path('routes/console.php');
    }
}
