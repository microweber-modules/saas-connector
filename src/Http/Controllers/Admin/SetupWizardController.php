<?php
namespace MicroweberPackages\Modules\SaasConnector\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Install\TemplateInstaller;

class SetupWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        $siteTemplates = site_templates();

        return view('saas_connector::setup-wizard', [
            'siteTemplates' => $siteTemplates,
        ]);
    }

    public function installTemplate(Request $request) {

        $template = $request->post('template', false);
        if (!empty($template)) {

            $installer = new TemplateInstaller();
            $installer->logger = new TemplateInstallerLogger();
            $installer->setDefaultTemplate($template);

            $hasDefaultContent = $installer->installTemplateContent($template);
            if (!$hasDefaultContent) {
                $installer->createDefaultContent();
            }

            return response()->json([
                'success' => true,
            ]);
        }

    }
}

class TemplateInstallerLogger {

    public $log = '';

    public function setLogInfo($text)
    {
        return $this->log($text);
    }

    public function log($text)
    {
        $this->log = $text;
    }

    public function clearLog()
    {
        $this->log = '';
    }

}
