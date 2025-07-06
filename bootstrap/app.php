<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // --- THIS IS THE MERGED MIDDLEWARE CONFIGURATION ---

        // Register the route middleware aliases for the Spatie Permissions package.
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // You can add other global middleware or middleware groups here if needed.
        // For example:
        // $middleware->web(append: [
        //     \App\Http\Middleware\ExampleMiddleware::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configure your exception handling here.
    })
    ->withProviders([
        // --- THIS IS WHERE YOU REGISTER YOUR CUSTOM SERVICE PROVIDERS ---
        
        // Example for a Communications module
        // \App\Modules\Communications\Providers\CommunicationsServiceProvider::class,
        
        // If you create other modules in the future, add their providers here.
    ])
    ->create(); // <-- The create() method MUST be the very last call.