<?php

Route::middleware(['admin'])
    ->name('saas-connector.')
    ->namespace('MicroweberPackages\Modules\SaasConnector\Http\Controllers\Admin')
    ->group(function () {

        Route::get('/setup-wizard', 'SetupWizardController@index')->name('setup-wizard');
        Route::post('/install-template', 'SetupWizardController@installTemplate')->name('install-template');

    });
