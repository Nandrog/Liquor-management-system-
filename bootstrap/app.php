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
    ->withMiddleware(function (Middleware $middleware) { // <-- The parameter name can be whatever you want, e.g., $middleware
        
        // This is the block you need to add to register the aliases.
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) { // <-- Corrected the parameter name here too for consistency
        //
    })->create();
