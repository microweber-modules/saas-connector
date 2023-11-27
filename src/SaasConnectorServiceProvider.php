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
                        'icon' => '<svg version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
	 viewBox="0 0 512 512"  xml:space="preserve">
<style type="text/css">
	.st0{fill:#000000;}
</style>
<g>
	<path class="st0" d="M0,0.002v511.996h512v-18.071V0.002H0z M475.859,475.856H36.141v-364.43h439.718V475.856z"/>
	<rect x="78.305" y="167.994" class="st0" width="355.386" height="96.723"/>
	<rect x="295.152" y="309.894" class="st0" width="138.538" height="118.968"/>
	<rect x="78.305" y="311.694" class="st0" width="162.631" height="18.07"/>
	<rect x="78.305" y="408.991" class="st0" width="162.631" height="18.071"/>
	<rect x="78.305" y="360.347" class="st0" width="162.631" height="18.071"/>
</g>
</svg>'
                    ]
                ])->setExtra('orderNumber', 1);

            \MicroweberPackages\LiveEdit\Facades\LiveEditManager::getMenuInstance('top_right_menu')
                ->addChild('Plans and Payments', [
                    'attributes' => [
                        'href' => $websiteManagerUrl . '/projects/plans',
                        'icon' => '<svg viewBox="0 0 40 32.29">
                                <path
                                    d="M40 3v26c0 .8-.3 1.5-.9 2.1-.6.6-1.3.9-2.1.9H3c-.8 0-1.5-.3-2.1-.9-.6-.6-.9-1.3-.9-2.1V3C0 2.2.3 1.5.9.9 1.5.3 2.2 0 3 0h34c.8 0 1.5.3 2.1.9.6.6.9 1.3.9 2.1zM3 8.45h34V3H3v5.45zm0 6.45V29h34V14.9H3zM3 29V3v26z"/>
                            </svg>'
                    ]
                ])->setExtra('orderNumber', 2);
        }

    }
}
