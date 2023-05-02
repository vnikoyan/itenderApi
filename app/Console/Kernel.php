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
        \App\Console\Commands\UnApprovedType\UnApprovedType::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType1::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType2::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType3::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType4::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType5::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType6::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType7::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType8::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType81::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType9::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType10::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType11::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType12::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType121::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType122::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType123::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType124::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType13::class,
        // \App\Console\Commands\UnApprovedType\UnApprovedType14::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('GetCpvsPotential:get')
        //             ->monthly();
        $schedule->command('OrganizeItender:update')
                    ->everyMinute();
        $schedule->command('Organize:update')
                    ->everyMinute();
        $schedule->command('CpvTranslat:update')
                    ->everyMinute();
        $schedule->command('ContractsOrders:update')
                    ->daily();
        $schedule->command('ProcurementAnnouncements:get')
                    ->hourly(5);
        $schedule->command('archive:tenders')
                    ->everyMinute(); 
        $schedule->command('user-corresponding-tenders:filter')
                    ->hourly();  
        $schedule->command('electronicAuctionSchedule:get')
                    ->hourly();              
        $schedule->command('TenderState:update')
                    ->hourly();        
        $schedule->command('OrdersSchedule:notify')
                    ->hourly();        
        $schedule->command('FavoriteTeners:notify')
                    ->hourly();
        $schedule->command('usersDailyEmail:notify')
                    ->dailyAt('10:00');
        $schedule->command('usersDailySecondEmail:notify')
                    ->dailyAt('19:00');
        $schedule->command('removePdfAndHtml:files')
                    ->daily();
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
