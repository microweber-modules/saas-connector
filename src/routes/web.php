<?php

use App\Helper;

Route::middleware(['admin'])
    ->name('saas-connector.')
    ->namespace('MicroweberPackages\Modules\SaasConnector\Http\Controllers\Admin')
    ->group(function () {

        Route::get('/setup-wizard', 'SetupWizardController@index')->name('setup-wizard');
        Route::post('/install-template', 'SetupWizardController@installTemplate')->name('install-template');

    });

Route::middleware(['xss'])
    ->name('saas-connector.')
    ->namespace('MicroweberPackages\Modules\SaasConnector\Http\Controllers')
    ->group(function () {

        Route::any('/login-with-token', 'LoginWithTokenController@index')->name('login-with-token');
        Route::get('/ads-bar', 'AdsBarController@index')->name('ads-bar');

        Route::get('/saas-clearcache', function (){

            $token = request()->get('token', false);

            if (validateLoginWithToken($token)) {
                clearcache();
                return 'Cache cleared';
            }
            return redirect(admin_url());

        })->name('saas-clearcache');

    });
