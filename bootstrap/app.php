<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'diwan' => \App\Http\Middleware\Diwan::class,
            'copier' => \App\Http\Middleware\Copier::class,
            'reviewer' => \App\Http\Middleware\Review::class,
        ]);
        $middleware->redirectGuestsTo('/');

        $middleware->redirectUsersTo(fn () => match (Auth::user()->role) {
            0       => '/diwan_p',
            1       => '/copy_p',
            2       => '/review_p',
            // default => '/dashboard',
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
