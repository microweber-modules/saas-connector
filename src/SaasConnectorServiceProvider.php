<?php

namespace MicroweberPackages\Modules\SaasConnector;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use MicroweberPackages\LiveEdit\Events\ServingLiveEdit;

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


        Event::listen(ServingLiveEdit::class, [$this, 'registerMenu']);
    }

    public function registerMenu()
    {
        $websiteManagerUrl = \Cache::remember('websiteManagerUrl', 10000, function () {
            return getWebsiteManagerUrl();
        });

        if ($websiteManagerUrl) {
            \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
                ->addChild('Back to My Websites', [
                    'attributes' => [
                        'href' => $websiteManagerUrl . '/projects',
                        'icon' => '<svg viewBox="0 0 40 32.29">
                                <path
                                    d="M40 3v26c0 .8-.3 1.5-.9 2.1-.6.6-1.3.9-2.1.9H3c-.8 0-1.5-.3-2.1-.9-.6-.6-.9-1.3-.9-2.1V3C0 2.2.3 1.5.9.9 1.5.3 2.2 0 3 0h34c.8 0 1.5.3 2.1.9.6.6.9 1.3.9 2.1zM3 8.45h34V3H3v5.45zm0 6.45V29h34V14.9H3zM3 29V3v26z"/>
                            </svg>'
                    ]
                ]);

            \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
                ->addChild('Plans and Payments', [
                    'attributes' => [
                        'href' => $websiteManagerUrl . '/projects/plans',
                        'icon' => '<svg viewBox="0 0 40 32.29">
                                <path
                                    d="M40 3v26c0 .8-.3 1.5-.9 2.1-.6.6-1.3.9-2.1.9H3c-.8 0-1.5-.3-2.1-.9-.6-.6-.9-1.3-.9-2.1V3C0 2.2.3 1.5.9.9 1.5.3 2.2 0 3 0h34c.8 0 1.5.3 2.1.9.6.6.9 1.3.9 2.1zM3 8.45h34V3H3v5.45zm0 6.45V29h34V14.9H3zM3 29V3v26z"/>
                            </svg>'
                    ]
                ]);
        }

    }
}
