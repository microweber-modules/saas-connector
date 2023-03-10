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


event_bind('mw.front----', function () {

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

//    if (route::has('saas-connector.ads-bar')) {
//        return false;
//    }

//    $url = route('saas-connector.ads-bar');
    if (is_live_edit()) {
        $url = '/ads-bar?live_edit=1';
    } else {
        $url = '/ads-bar';
    }

    mw()->template->foot($css . '<div class="js-microweber-add-iframe-wrapper">
                 <iframe class="js-microweber-add-iframe" scrolling="no" frameborder="0" src="'.$url.'"></iframe>
            </div>');

});