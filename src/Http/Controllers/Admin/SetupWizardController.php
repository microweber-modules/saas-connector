<?php
namespace MicroweberPackages\Modules\SaasConnector\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Install\TemplateInstaller;

class SetupWizardController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        $filterCategory = $request->get('category', false);
        $getCategories = [];
        $siteTemplates = [];
        $getTemplates = site_templates();

        foreach ($getTemplates as $template) {

            if (!isset($template['screenshot'])) {
                continue;
            }

            $templateCategories = [];
            $templateColors = [];
            $templateJson = templates_path() . $template['dir_name'] . '/composer.json';

            if (is_file($templateJson)) {
                $templateJson = @file_get_contents($templateJson);
                $templateJson = @json_decode($templateJson, true);
                if (!empty($templateJson)) {

                    if (isset($templateJson['extra']['colors'])) {
                        $templateColors = $templateJson['extra']['colors'];
                    }

                    $templateCategories = [];
                    if (isset($templateJson['keywords']) and !empty($templateJson['keywords'])) {
                        foreach ($templateJson['keywords'] as $keyword) {
                            if (strpos(mb_strtolower($keyword), 'microweber') !== false) {
                                continue;
                            }
                            $templateCategories[] = [
                                'name' => ucwords($keyword),
                                'slug' => \Str::slug($keyword),
                            ];
                            $getCategories[$keyword] = $keyword;
                        }
                    }
                }
            }

            $findedCategoryByFilter = false;
            if ($filterCategory) {
                if (!empty($templateCategories)) {
                    foreach ($templateCategories as $category) {
                        if ($category['slug'] == $filterCategory) {
                            $findedCategoryByFilter = true;
                        }
                    }
                }
                if (!$findedCategoryByFilter) {
                    continue;
                }
            }

            $template['categories'] = $templateCategories;
            $template['colors'] = $templateColors;

            $siteTemplates[] = $template;
        }

        $siteTemplateCategories = [];
        $cleanCategories = array_values($getCategories);
        foreach ($cleanCategories as $category) {
            $siteTemplateCategories[] = [
                'name' => ucwords($category),
                'slug' => \Str::slug($category),
            ];
        }

        $installTemplate = $request->get('install_template', false);
        if ($installTemplate) {
            $this->__installTemplate($installTemplate);
            return redirect(admin_url() . 'live-edit');
        }

        return view('saas_connector::setup-wizard', [
            'siteTemplates' => $siteTemplates,
            'siteTemplateCategories' => $siteTemplateCategories,
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
