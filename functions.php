<?php


function getWebsiteManagerUrl()
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

event_bind('mw.admin.header.toolbar.ul', function () {

    $saasUrl = getWebsiteManagerUrl();

    echo '<a href="'.$saasUrl.'/projects" 
                style="border-radius: 40px;" class="btn btn-outline-primary">
           <i class="mdi mdi-arrow-left"></i> &nbsp; My Websites
        </a>';
});


event_bind('live_edit_toolbar_action_buttons', function () {

    $saasUrl = getWebsiteManagerUrl();

    echo '<a href="'.$saasUrl.'/projects" 
              class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert">
           <i class="mdi mdi-arrow-left"></i> &nbsp; My Websites
        </a>';
});


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

    $url = route('saas-connector.ads-bar');

    mw()->template->foot($css . '<div class="js-microweber-add-iframe-wrapper">
                 <iframe class="js-microweber-add-iframe" scrolling="no" frameborder="0" src="'.$url.'"></iframe>
            </div>');

});