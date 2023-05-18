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
            $checkWebsite = app()->http->url($websiteManagerUrl . '/api/websites/website-info?domain=' . $checkDomain)->get();
            $checkWebsite = @json_decode($checkWebsite, true);

            if (isset($checkWebsite['success']) && $checkWebsite['success']) {
                $checkWebsiteCache = $checkWebsite;
                return $checkWebsite;
            }
        } catch (\Exception $e) {
            return false;
        }

    }

}

function canIShowAdsBar()
{

    $checkWebsite = getSaasWebsiteInfoFromServer();
    if (isset($checkWebsite['showAdsBar']) && $checkWebsite['showAdsBar']) {
        return true;
    }

    return false;
}
function canIShowExternalAds()
{

    $checkWebsite = getSaasWebsiteInfoFromServer();
    if (isset($checkWebsite['showExternalAds']) && $checkWebsite['showExternalAds']) {
        return true;
    }

    return false;
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
        return true;
    }

    return false;
}

function getWebsiteManagerUrl()
{
    $brandingFile = storage_path('branding.json');
    if (is_file($brandingFile)) {
        $branding = json_decode(file_get_contents($brandingFile), true);
        if (isset($branding['website_manager_url']) && !empty($branding['website_manager_url'])) {
            $websiteManagerUrl = $branding['website_manager_url'];
            $parseUrl = parse_url($websiteManagerUrl);
            if (!empty($parseUrl['host'])) {
                return $parseUrl['scheme'] . '://' . $parseUrl['host'];
            }
        }

    }
    return false;
}

event_bind('mw.admin.header.toolbar.ul', function () {

    $saasUrl = getWebsiteManagerUrl();

    echo '<a href="' . $saasUrl . '/projects" 
                style="border-radius: 40px;" class="btn btn-outline-primary">
           <i class="mdi mdi-arrow-left"></i> &nbsp; My Websites
        </a>';

});

event_bind('live_edit_toolbar_action_buttons', function () {

    $saasUrl = getWebsiteManagerUrl();

    echo '<a href="' . $saasUrl . '/projects" 
              class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert">
           <i class="mdi mdi-arrow-left"></i> &nbsp; My Websites
        </a>';
});


event_bind('admin_head', function () {

    echo '
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "g6d42i7m8h");
</script>';

});


event_bind('mw.front', function () {

    mw()->template->foot('
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "g6d42i7m8h");
</script>');

});
if (canIShowExternalAds() and !in_live_edit()) {

    event_bind('mw.front', function () {
        mw()->template->foot('<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4602630167939881"
     crossorigin="anonymous"></script>');
    });
}

if (canIShowAdsBar() and !in_live_edit()) {

    event_bind('mw.front', function () {

        $css = '
        <style>
        .js-microweber-add-iframe-wrapper {
            height: 40px;
            width: 100%;
            min-height: 40px !important;
            max-height: 40px !important;
            position:relative;
        }
        .js-microweber-add-iframe {
            z-index: 99999;
            position: fixed;
            min-height: 0;
            height: 40px !important;
            border: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            overflow: hidden;
        }
        </style>
    ';

        if (is_live_edit()) {
            $url = '/ads-bar?live_edit=1';
        } else {
            $url = '/ads-bar';
        }

        mw()->template->foot($css . '<div class="js-microweber-add-iframe-wrapper">
                 <iframe class="js-microweber-add-iframe" scrolling="no" frameborder="0" src="' . $url . '"></iframe>
            </div>');

    });
}