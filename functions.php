<?php
function getSaasWebsiteInfoFromServer()
{
    static $checkWebsiteCache;

    if(is_array($checkWebsiteCache) and !empty($checkWebsiteCache)){
        return $checkWebsiteCache;
    }

    $websiteManagerUrl = getWebsiteManagerUrl();
    if (!$websiteManagerUrl) {
        return false;
    }

    $checkDomain = site_url();
    $parseUrl = parse_url($checkDomain);
    if (!empty($parseUrl['host'])) {

        try {
            $checkDomain = $parseUrl['host'];

            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET',$websiteManagerUrl . '/api/websites/website-info?domain=' . $checkDomain, [

            ]);
//            echo $res->getStatusCode();
//// "200"
//            echo $res->getHeader('content-type')[0];
//// 'application/json; charset=utf8'
//            echo $res->getBody();

            $checkWebsite = $res->getBody();
            //$checkWebsite = app()->http->set_cache(0)->url($websiteManagerUrl . '/api/websites/website-info?domain=' . $checkDomain)->get();
            $checkWebsite = @json_decode($checkWebsite, true);
  //dd($checkWebsite);
            if (isset($checkWebsite['success']) && $checkWebsite['success']) {
                $checkWebsiteCache = $checkWebsite;
                return $checkWebsite;
            }
        } catch (\Exception $e) {
            return false;
        }

    }

}

$checkWebsite = getSaasWebsiteInfoFromServer();

$canIShowAdsBar = false;
if (isset($checkWebsite['showAdsBar']) && $checkWebsite['showAdsBar']) {
    $canIShowAdsBar = true;
}

$canIShowExternalAds = false;
if (isset($checkWebsite['showExternalAds']) && $checkWebsite['showExternalAds']) {
    $canIShowExternalAds = true;
}

function validateLoginWithToken($token)
{
    $parse = parse_url(site_url());
    if (!isset($parse['host'])) {
        return false;
    }

    $domain = $parse['host'];
    $domain = str_replace('www.', '', $domain);

    $websiteManagerUrl = getWebsiteManagerUrl();
    if (!$websiteManagerUrl) {
        return false;
    }

    $verifyUrl = $websiteManagerUrl . '/api/websites/verify-login-token?token=' . $token . '&domain=' . $domain;
    $verifyCheck = @app()->http->url($verifyUrl)->get();
    $verifyCheck = @json_decode($verifyCheck, true);

    if (isset($verifyCheck['success']) && $verifyCheck['success'] == true && isset($verifyCheck['token']) && $verifyCheck['token'] == $token) {
        return $verifyCheck;
    }

    return false;
}
function getBranding()
{
    $brandingFile = storage_path('branding_saas.json');
    if (is_file($brandingFile)) {
        $branding = json_decode(file_get_contents($brandingFile), true);
        if (!empty($branding)) {
            return $branding;
        }
    }

    $brandingFileUser = storage_path('branding.json');
    if (is_file($brandingFileUser)) {
        $branding = @json_decode(file_get_contents($brandingFileUser), true);
        if (!empty($branding)) {
            return $branding;
        }
    }

    return false;
}

function getWebsiteManagerUrl()
{


    $brandingFile = storage_path('branding_saas.json');
    if (is_file($brandingFile)) {
        $branding = json_decode(file_get_contents($brandingFile), true);
        if (isset($branding['website_manager_url']) && !empty($branding['website_manager_url'])) {
            $websiteManagerUrl = $branding['website_manager_url'];
            $parseUrl = parse_url($websiteManagerUrl);
            if (!empty($parseUrl['host'])) {
                return trim($websiteManagerUrl, '/');
            }
        }

    }

    $branding = [];
    $brandingFileUser = storage_path('branding.json');
    if (is_file($brandingFileUser)) {
        $branding = @json_decode(file_get_contents($brandingFileUser), true);

        if (isset($branding['website_manager_url']) && !empty($branding['website_manager_url'])) {
            $websiteManagerUrl = $branding['website_manager_url'];

            $parseUrl = parse_url($websiteManagerUrl);
            if (!empty($parseUrl['host'])) {
                return trim($websiteManagerUrl, '/');
            }
        }
    }



    return false;
}

event_bind('mw.admin.sidebar.li.first', function () {

    $saasUrl = getWebsiteManagerUrl();

    if ($saasUrl) {
        echo '<a href="' . $saasUrl . '/projects"
                    style="border-radius: 40px;" class="btn btn-outline-primary">
               <i class="mdi mdi-arrow-left"></i> &nbsp; My Websites
            </a>';
    }
});

//event_bind('live_edit_toolbar_action_buttons', function () {
//
//    $saasUrl = getWebsiteManagerUrl();
//
//    echo '<a href="' . $saasUrl . '/projects"
//              class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert">
//           <i class="mdi mdi-arrow-left"></i> &nbsp; My Websites
//        </a>';
//});


if (isset($checkWebsite['appendScriptsAdminPanel']) && !empty($checkWebsite['appendScriptsAdminPanel'])) {
    event_bind('admin_head', function () use($checkWebsite) {
        echo $checkWebsite['appendScriptsAdminPanel'];
    });
}

if (isset($checkWebsite['success'])) {

    $hasActiveSubscription = false;
    if (isset($checkWebsite['activeSubscription']) && !empty($checkWebsite['activeSubscription'])) {
        $hasActiveSubscription = true;
    }

    define('HAS_ACTIVE_SUBSCRIPTION', $hasActiveSubscription);

    if (isset($_POST['hidden_preview'])) {
        if (isset($_POST['password_preview'])) {
            $password = $_POST['password_preview'];
            $password = trim($password);
            $domain = $checkWebsite['domain'];

            $websiteManagerUrl = getWebsiteManagerUrl();
            if (!$websiteManagerUrl) {
                return false;
            }

            $verifyUrl = $websiteManagerUrl . '/api/websites/validate-password-preview';

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $verifyUrl,
                CURLOPT_USERAGENT => 'Microweber',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'password' => $password,
                    'domain' => $domain
                )
            ));
            $verifyCheck = curl_exec($curl);
            $verifyCheck = @json_decode($verifyCheck, true);
            if (isset($verifyCheck['success']) && $verifyCheck['success'] == true) {
                app()->user_manager->session_set('hidden_preview', 1);
            }
        }
    }

    if (!$hasActiveSubscription) {
        event_bind('mw.front', function () use ($checkWebsite) {
            
            $isHiddenPreview = false;
            if (app()->user_manager->session_get('hidden_preview')) {
                $isHiddenPreview = true;
            }

            // SHOW WEBSITE PASSWORD PROTECTED PREVIEW
            if (isset($_GET['hidden_preview'])) {
                if (!in_live_edit() && !user_id()) {
                    echo view('saas_connector::hidden-website-preview', [

                    ]);
                    exit;
                }
            }

            // SHOW UPGRADE PLAN
            if (!$isHiddenPreview) {
                echo view('saas_connector::upgrade-plan', [
                    'branding' => getBranding(),
                ]);
                exit;
            }
        });
    }
}

event_bind('mw.front', function () use($checkWebsite) {

    if (!in_live_edit()) {
        if (isset($checkWebsite['appendScriptsFrontendLogged']) && !empty($checkWebsite['appendScriptsFrontendLogged'])) {
            if (user_id()) {
                mw()->template->foot($checkWebsite['appendScriptsFrontendLogged']);
            }
        }

        if (isset($checkWebsite['appendScriptsFrontend']) && !empty($checkWebsite['appendScriptsFrontend'])) {
            if (!user_id()) {
                mw()->template->foot($checkWebsite['appendScriptsFrontend']);
            }
        }
    } else {
        if (isset($checkWebsite['appendScriptsLiveEdit']) && !empty($checkWebsite['appendScriptsLiveEdit'])) {
            mw()->template->foot($checkWebsite['appendScriptsLiveEdit']);
        }
    }

});

//if ($canIShowExternalAds and !in_live_edit()) {
//
//    if (isset($checkWebsite['appendScriptsExternalAdsFrontend']) && !empty($checkWebsite['appendScriptsExternalAdsFrontend'])) {
//        event_bind('mw.front', function () use($checkWebsite) {
//            mw()->template->foot($checkWebsite['appendScriptsExternalAdsFrontend']);
//        });
//    }
//}
//
//if ($canIShowAdsBar and !in_live_edit()) {
//
//    event_bind('mw.front', function () {
//
//        $css = '
//        <style>
//        .js-microweber-add-iframe-wrapper {
//            height: 40px;
//            width: 100%;
//            min-height: 40px !important;
//            max-height: 40px !important;
//            position:relative;
//        }
//        .js-microweber-add-iframe {
//            z-index: 99999;
//            position: fixed;
//            min-height: 0;
//            height: 40px !important;
//            border: 0;
//            left: 0;
//            right: 0;
//            bottom: 0;
//            width: 100%;
//            overflow: hidden;
//        }
//        </style>
//    ';
//
//        if (is_live_edit()) {
//            $url = '/ads-bar?live_edit=1';
//        } else {
//            $url = '/ads-bar';
//        }
//
//        mw()->template->foot($css . '<div class="js-microweber-add-iframe-wrapper">
//                 <iframe class="js-microweber-add-iframe" scrolling="no" frameborder="0" src="' . $url . '"></iframe>
//            </div>');
//
//    });
//}
