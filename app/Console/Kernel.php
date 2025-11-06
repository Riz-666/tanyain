<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Hapus artikel & repositori >20 hari
        $schedule->command('artikel:purge')->daily();
        $schedule->command('repositori:purge')->daily();

        // Hapus user expired >20 hari
        $schedule->command('users:purge')->daily();

        // Hapus draft expired + temp file terkait
        $schedule->command('drafts:purge')->hourly();
        $schedule->command('temp:purge')->hourly();

        // Hapus saran >10 hari
        $schedule->command('saran:delete-old')->daily();

        // Cleanup notifikasi lama
        $schedule->command('notifikasi:cleanup')->hourly();

        // Update monthly summary
        $schedule->command('summary:update-monthly')->monthlyOn(1, '00:00');
    }

    // protected function schedule(Schedule $schedule)
    // {
    //     $schedule->command('artikel:purge')->everyMinute();
    //     $schedule->command('repositori:purge')->everyMinute();
    //     $schedule->command('users:purge')->everyMinute();
    //     $schedule->command('drafts:purge')->everyMinute();
    //     $schedule->command('temp:purge')->everyMinute();

    // }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
