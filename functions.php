<?php


function getBillingDropdown()
{
    return '   <div class="btn-group btn-group-sm" role="group">
        <button type="button" style="border-radius: 40px;" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Bozhidar Slaveykov
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="https://saas.microweber.bg/projects">My Websites</a></li>
            <li><a class="dropdown-item" href="https://saas.microweber.bg/projects/profile">My Profile</a></li>
            <li><a class="dropdown-item" href="https://saas.microweber.bg/checkout/billing-portal">Billing</a></li>
            <li><a class="dropdown-item" href="https://saas.microweber.bg/checkout/billing-portal">Invoices</a></li>
            <li><a class="dropdown-item" href="https://saas.microweber.bg/projects/profile">Settings</a></li>
            <li><a class="dropdown-item" href="#"">Logout</a></li>
        </ul>
    </div>'; 
}

event_bind('mw.admin.header.toolbar.ul', function () {
    echo getBillingDropdown();
});


event_bind('live_edit_toolbar_action_buttons', function () {
    echo getBillingDropdown();
});
