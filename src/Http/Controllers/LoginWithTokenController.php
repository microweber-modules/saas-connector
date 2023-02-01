<?php
namespace MicroweberPackages\Modules\SaasConnector\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MicroweberPackages\User\Models\User;

class LoginWithTokenController extends Controller
{
    public function index(Request $request) {

        $redirect = $request->get('redirect', false);

        $token = $request->get('token', false);
        if (empty($token)) {
            return redirect(admin_url());
        }

        $parse = parse_url(site_url());
        if (!isset($parse['host'])) {
            return redirect(admin_url());
        }

        $domain = $parse['host'];
        $domain = str_replace('www.', '', $domain);

        $websiteManagerUrl = $this->getWebsiteManagerUrl();

        if (!$websiteManagerUrl) {
            return redirect(admin_url());
        }

        $verifyUrl = $websiteManagerUrl . '/api/websites/verify-login-token?token=' . $token . '&domain=' . $domain;
        $verifyCheck = @app()->http->url($verifyUrl)->get();
        $verifyCheck = @json_decode($verifyCheck, true);

        if (isset($verifyCheck['success']) && $verifyCheck['success'] == true && isset($verifyCheck['token']) && $verifyCheck['token'] == $token) {
            $user = User::where('is_admin', '=', '1')->first();
            if ($user !== null) {
                \Illuminate\Support\Facades\Auth::login($user);
                if (!empty($redirect)) {
                    return redirect($redirect);
                }
            }

            return redirect(admin_url());
        }

        return redirect(admin_url());
    }

    public function getWebsiteManagerUrl()
    {
        $brandingFile = storage_path('branding.json');
        if (is_file($brandingFile)) {
            $branding = json_decode(file_get_contents($brandingFile), true);
            if (isset($branding['website_manager_url']) && !empty($branding['website_manager_url'])) {
                $websiteManagerUrl = $branding['website_manager_url'];
                $parseUrl = parse_url($websiteManagerUrl);
                if (!empty($parseUrl['host'])) {
                    return $parseUrl['scheme'] .'://'. $parseUrl['host'];
                }
            }

        }
        return false;
    }
}
