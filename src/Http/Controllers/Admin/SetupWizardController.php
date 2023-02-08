<?php
namespace MicroweberPackages\Modules\SaasConnector\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Install\TemplateInstaller;

class SetupWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        $siteTemplates = [];
        $getTemplates = site_templates();
        foreach ($getTemplates as $template) {
            if (!isset($template['screenshot'])) {
                continue;
            }

            $template['screenshot'] = thumbnail($template['screenshot'], 600, 500, true);

            $siteTemplates[] = $template;
        }

        $installTemplate = $request->get('install_template', false);
        if ($installTemplate) {
            $this->__installTemplate($installTemplate);
            return redirect(site_url() . '?editmode=y');
        }

        return view('saas_connector::setup-wizard', [
            'siteTemplates' => $siteTemplates,
        ]);
    }

    public function installTemplate(Request $request)
    {
        $template = $request->post('template', false);

        return $this->__installTemplate($template);
    }

    private function __installTemplate($template) {

        if (!empty($template)) {

            $installer = new TemplateInstaller();
            $installer->logger = new TemplateInstallerLogger();
            $installer->setDefaultTemplate($template);

            $hasDefaultContent = $installer->installTemplateContent($template);
            if (!$hasDefaultContent) {
                $installer->createDefaultContent();
            }

            save_option('mw_setup_wizard_completed', 1, 'website');

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
