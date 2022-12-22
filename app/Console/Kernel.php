<?php

namespace App\Console;


use App\Jobs\UpdateCurrencyJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

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
		$schedule->job(new UpdateCurrencyJob())->delay(Carbon::now()->addMinutes(1)->when(function () {
			return true;
		}));
		//$schedule->job(UpdateCurrencyJob::class)->dailyAt('16:00');
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
