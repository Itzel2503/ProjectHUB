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
        // Ejecutar el comando todos los días a las 8:00 AM
        $schedule->command('command:activitiesRecurrent')->everyMinute();
        // $schedule->command('command:activitiesRecurrent')->dailyAt('05:00');

        // Otras opciones:
        // ->everyMinute(); // Cada minuto
        // ->hourly(); // Cada hora
        // ->daily(); // Cada día a medianoche
        // ->weekly(); // Cada semana
        // ->monthly(); // Cada mes
        // ->cron('* * * * *'); // Usando expresión cron personalizada
        /*
        **
            Minuto (0 - 59)
            Hora (0 - 23, formato de 24 horas)
            Día del mes (1 - 31)
            Mes (1 - 12)
            Día de la semana (0 - 6 donde 0 es domingo y 6 es sábado)
        ** 
        */
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    // protected function commands()
    // {
    //     $this->load(__DIR__.'/Commands');

    //     require base_path('routes/console.php');
    // }

    protected $commands = [
        \App\Console\Commands\ActivitiesRecurrent::class,
    ];
}
