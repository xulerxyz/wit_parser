<?php

namespace Nigel\WitParser;

use Illuminate\Support\ServiceProvider;

class WitParserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(WitParserService::class, function ($app) {
            return new WitParserService();
        });

        $this->app->singleton(WitManagerService::class, function ($app) {
            return new WitManagerService();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/wit.php' => config_path('wit.php'),
        ], 'config');
    }
} 