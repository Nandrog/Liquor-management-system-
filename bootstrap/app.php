<?php

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // This loads the main application routes.
        // Your module routes are loaded by your service provider.
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // This is where you would add global middleware if needed.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // This is where you configure exception handling.
    })
    ->withProviders([
        // THIS IS THE CORRECT PLACE TO REGISTER YOUR MODULE'S PROVIDER
        \App\Modules\Communications\Providers\CommunicationsServiceProvider::class,
        
        // If you create other modules in the future, add their providers here.
    ])
    ->create();