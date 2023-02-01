<?php

namespace MicroweberPackages\Modules\SaasConnector;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class SaasConnectorServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom( __DIR__. '/resources/views/components', 'saas_connector');
        // Livewire::component('saas_connector::install', Install::class);
    }

    public function register()
    {
        // $this->loadMigrationsFrom((__DIR__) . '/migrations/');

        View::addNamespace('saas_connector', normalize_path((__DIR__) . '/resources/views'));
    }
}
