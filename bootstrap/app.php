<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // the "login" route no longer exists; guests land on the home page
        $middleware->redirectGuestsTo(fn () => route('home'));

        $middleware->alias([


            // Add your custom middleware here
            'team.member' => \App\Http\Middleware\CheckTeamMembership::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withSchedule(function (Schedule $schedule) {
        $schedule->command('reservations:cleanup')->hourly();

        /*
         * The planners edit the routing sheets constantly. Checking every
         * minute costs a stat() per sheet; the workbook is only re-read when
         * its size or timestamp has actually changed.
         */
        if (config('sheets.watch')) {
            foreach (config('sheets.routes', []) as $source => $path) {
                $schedule->command('routes:import-sheet', [
                    $path, '--source=' . $source, '--if-changed',
                ])->everyMinute()->withoutOverlapping();
            }
        }
    })->create();
