<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['country'] = 'Ec-bp';

$urlWs = 'http://172.24.6.78:10003/NovoEolWebInterfaceWS/webresources/';
if(ENVIRONMENT == 'development') {
	$urlWs = 'http://172.24.6.78:10003/NovoEolWebInterfaceWS/webresources/';
}
$config['urlWS'] = $urlWs;
$config['base_url']	= BASE_URL.'/bp/';
$config['language']	= 'bp-spanish';
$config['favicon'] = 'favicon-bp';
