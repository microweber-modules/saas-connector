<?php

Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\SaasConnector\Http\Controllers\Admin')
    ->group(function () {



    });
