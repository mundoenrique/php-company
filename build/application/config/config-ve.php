<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['country'] = 'Ve';
$config['base_url']	= BASE_URL.'ve/';
$config['language']	= 'spanish-ve';
// Config elements master_content
$config['settingContents'] =
[
	'master_content' => [
		'menuFooter' => TRUE,
		'ownerShip' => 'http://www.novopayment.com/',
		'logo' => TRUE,
		'showRates' => TRUE
	],
	'widget_menu-user' => [
		'menuTop' => 'extended',
		'optionHelp' => TRUE
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
		'showInfoPass' => TRUE
	]
];
