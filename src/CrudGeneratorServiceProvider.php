<?php

namespace Naowas\CrudGenerator;

use Illuminate\Support\ServiceProvider;
use Naowas\CrudGenerator\Commands\GenerateCrud;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadViewsFrom(__DIR__.'/../stubs', 'CrudGenerator');

        $this->publishes([
            __DIR__ . '/../publish/views/' => base_path('resources/views/'),
        ]);

        $this->publishes([
            __DIR__ . '/../stubs/' => base_path('resources/crud-generator/stubs'),
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->commands([
            GenerateCrud::class,
        ]);
    }
}
