<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['country'] = 'Ec-bp';
$config['base_url']	= BASE_URL.'bp/';
$config['language']	= 'spanish-bp';
$config['favicon'] = 'favicon-bp';
$config['client'] = 'pichincha';

// Menu user/config diseño de unica columna
$config['settingContents'] =
[
	'master_content' => [
		'menuFooter' => FALSE,
		'ownerShip' => FALSE,
		'logo' => FALSE,
		'showRates' => FALSE
	],
	'widget_menu-user' => [
		'menuTop' => 'unique',
		'optionHelp' => FALSE
	],
	'widget_menu-business' => [
		'menuPrincipalFull' => TRUE
	],
	'enterprise_content' => [
		'typeFilterEnterprise' => 'select',
		'typeOverDetailCompanies' => 'over',
		'showRazonSocialDetailCompanies' => TRUE,
	],
	'signin_content' => [
		'loginTitle' => FALSE,
		'welcomeMessage' => TRUE
	],
	'change-password_content' => [
		'showInfoPass' => FALSE
	]
];
