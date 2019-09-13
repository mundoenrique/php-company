<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['country'] = 'Ec-bp';
$config['base_url']	= BASE_URL.'bp/';
$config['language']	= 'bp-spanish';
$config['language_file']	= ['general'];
$config['language_file_specific'] = ['general','login', 'terms'];

$config['favicon'] = 'favicon-bp';
// Menu user/config diseño de unica columna
$config['uniqueMenuUser'] = TRUE;
// Config elements master_content
$config['settingContents'] =
[
	'master_content' => [
		'menuFooter' => FALSE,
		'ownerShip' => FALSE
	],
	'widget_menu-user' => [
		'menuTop' => 'unique'
	],
	'widget_menu-business' => [
		'menuPrincipalFull' => FALSE
	],
	'enterprise_content' => [
		'typeFilterEnterprise' => 'select',
		'typeOverDetailCompanies' => 'over',
		'showRazonSocialDetailCompanies' => FALSE
	]
];
