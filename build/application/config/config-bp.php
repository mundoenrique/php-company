<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['country'] = 'Ec-bp';
$config['base_url']	= BASE_URL.'bp/';
$config['language']	= 'bp-spanish';
$config['language_file']	= ['general'];
$config['language_file_specific'] = ['general','login', 'terms'];

$config['favicon'] = 'favicon-bp';

// Menu user/config diseño de unica columna
$config['settingContents'] =
[
	'master_content' => [
		'menuFooter' => TRUE,
		'ownerShip' => 'http://www.novopayment.com/',
		'logo' => TRUE,
		'showRates' => FALSE
	],
	'widget_menu-user' => [
		'menuTop' => 'extended',
		'optionHelp' => FALSE
	],
	'widget_menu-business' => [
		'menuPrincipalFull' => TRUE
	],
	'enterprise_content' => [
		'typeFilterEnterprise' => 'list',
		'typeOverDetailCompanies' => 'bottom',
		'showRazonSocialDetailCompanies' => TRUE
	],
	'signin_content' => [
		'loginTitle' => TRUE,
		'welcomeMessage' => FALSE
	],
	'change-password_content' => [
		'showInfoPass' => FALSE
	]
];
