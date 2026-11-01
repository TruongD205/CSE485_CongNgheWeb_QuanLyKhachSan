<?php

use App\Http\Middleware\CheckGuest;
use App\Http\Middleware\CheckLogin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'dangnhap' => CheckLogin::class, // dòng này sẽ trỏ tới file CheckLogin
            'khach' => CheckGuest::class,  // dòng này sẽ trỏ tới file CheckGuest
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
