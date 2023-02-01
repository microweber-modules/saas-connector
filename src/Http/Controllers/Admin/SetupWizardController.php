<?php
namespace MicroweberPackages\Modules\SaasConnector\Http\Controllers\Admin;

use Illuminate\Http\Request;

class SetupWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        $siteTemplates = site_templates();

        return view('saas_connector::setup-wizard', [
            'siteTemplates' => $siteTemplates,
        ]);
    }
}
