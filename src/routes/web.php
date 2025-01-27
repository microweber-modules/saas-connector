<?php

use App\Helper;

Route::middleware(['admin'])
    ->name('saas-connector.')
    ->namespace('MicroweberPackages\Modules\SaasConnector\Http\Controllers\Admin')
    ->group(function () {

        Route::get('/setup-wizard', 'SetupWizardController@index')->name('setup-wizard');
        Route::post('/install-template', 'SetupWizardController@installTemplate')->name('install-template');

    });

Route::middleware(['xss', 'web'])
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

        Route::get('/validate-password-preview', function () {

            $checkDomain = site_url();
            $parseUrl = parse_url($checkDomain);
            $checkDomain = $parseUrl['host'];

            $password = request()->get('password_preview', false);
            $password = trim($password);

            $websiteManagerUrl = getWebsiteManagerUrl();
            if (!$websiteManagerUrl) {
                return app()->user_manager->redirect(site_url());
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
                    'domain' => $checkDomain
                )
            ));
            $verifyCheck = curl_exec($curl);
            $verifyCheck = @json_decode($verifyCheck, true);
            if (isset($verifyCheck['success']) && $verifyCheck['success'] == true) {
                app()->user_manager->session_set('hidden_preview', 1);
            }

            return redirect(site_url());
        });

    });
