<?php
$config = array();
$config['name'] = "Saas Connector";
$config['author'] = "Microweber";

$config['categories'] = "admin";
$config['version'] = 0.8;
$config['ui_admin'] = true;
$config['ui'] = false;
$config['position'] = 99;

$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\SaasConnector\\'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\SaasConnector\SaasConnectorServiceProvider::class,
    \MicroweberPackages\Modules\SaasConnector\SaasConnectorRoutesServiceProvider::class,
];
