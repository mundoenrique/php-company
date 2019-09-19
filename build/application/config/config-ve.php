<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['country'] = 'Ve';
$config['base_url']	= BASE_URL.'ve/';
$config['language']	= 've-spanish';
$config['language_file_specific'] = ['login', 'pass-recovery', 'terms'];
// Config elements master_content
$config['settingContents'] =
[
	'master_content' => [
		'menuFooter' => TRUE,
		'ownerShip' => 'http://www.novopayment.com/',
		'logo' => FALSE
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
	]
];

