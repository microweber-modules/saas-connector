<?php

namespace MicroweberPackages\Modules\SaasConnector;

use MicroweberPackages\User\Models\User;
use MicroweberPackages\User\UserManager;

class UserManagerSaas extends UserManager
{
    public function login($params)
    {

        dd($params);

        if (isset($params['code_login']) && !empty($params['code_login'])) {

            if (!function_exists('get_whitelabel_whmcs_settings')) {
                return false;
            }

            $code = $_GET['code_login'];
            $parse = parse_url(site_url());
            if (!isset($parse['host'])) {
                return redirect(admin_url());
            }

            $domain = $parse['host'];
            $domain = str_replace('www.', '', $domain);

            $whmcsSettings = get_whitelabel_whmcs_settings();

            if (!isset($whmcsSettings['whmcs_url']) || empty($whmcsSettings['whmcs_url'])) {
                return redirect(admin_url());
            }

            $verifyUrl = $whmcsSettings['whmcs_url'] . '/index.php?m=microweber_addon&function=verify_login_code&code=' . $code . '&domain=' . $domain;

            $verifyCheck = @app()->http->url($verifyUrl)->get();
            $verifyCheck = @json_decode($verifyCheck, true);

            if (isset($verifyCheck['success']) && $verifyCheck['success'] == true && isset($verifyCheck['code']) && $verifyCheck['code'] == $code) {
                $user = User::where('is_admin', '=', '1')->first();
                if ($user !== null) {
                    \Illuminate\Support\Facades\Auth::login($user);

                    if (isset($_GET['http_redirect']) && !empty($_GET['http_redirect'])) {
                        return redirect($_GET['http_redirect']);
                    }
                }

                return redirect(admin_url());
            }

            return redirect(admin_url());
        }

        return parent::login($params);
    }
}
