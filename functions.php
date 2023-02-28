<?php

event_bind('mw.admin.header.toolbar.ul', function () {
    echo '<a href="https://saas.microweber.bg/projects" 
                style="border-radius: 40px;" class="btn btn-outline-primary">
           <i class="mdi mdi-arrow-left"></i> &nbsp; My Websites
        </a>';
});


event_bind('live_edit_toolbar_action_buttons', function () {
    echo '<a href="https://saas.microweber.bg/projects" 
              class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert">
           <i class="mdi mdi-arrow-left"></i> &nbsp; My Websites
        </a>';
});
