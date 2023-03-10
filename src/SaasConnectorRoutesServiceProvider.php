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

        event_bind('mw.front', function () {
            $setupWizardCheck = get_option('mw_setup_wizard_completed', 'website');
            $templateCheck = get_option('current_template', 'template');

            if (empty($setupWizardCheck) && empty($templateCheck)) {
                if (mw_is_installed() && is_admin()) {
                    if (request()->isMethod('get')) {
                        if (!Str::contains(request()->path(), 'setup-wizard')) {
                            abort(301, '', ['Location' => site_url('setup-wizard')]);
                            exit;
                        }
                    }
                }
            }
        });

    }
}
