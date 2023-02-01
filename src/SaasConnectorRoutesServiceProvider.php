<?php

namespace MicroweberPackages\Modules\SaasConnector;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class SaasConnectorRoutesServiceProvider extends RouteServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom((__DIR__) . '/routes/api.php');
        $this->loadRoutesFrom((__DIR__) . '/routes/admin.php');
        $this->loadRoutesFrom((__DIR__) . '/routes/web.php');

        $themeConfig = app()->template->get_config();
        if (empty($themeConfig)) {
            if (request()->isMethod('get')) {
                if (!Str::contains(request()->path(), 'setup-wizard')) {
                    header('Location: /setup-wizard'); // redirect to setup wizard
                    exit;
                }
            }
        }
    }
}
