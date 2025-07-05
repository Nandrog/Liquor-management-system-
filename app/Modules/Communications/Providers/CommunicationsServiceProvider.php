<?php

namespace App\Modules\Communications\Providers;

use Illuminate\Support\ServiceProvider;

class CommunicationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
           $this->loadViewsFrom(__DIR__.'/../Resources/views', 'communications');

                  $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
    }

