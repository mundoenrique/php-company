<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['CONF_SCORE_CAPTCHA'] = [
	'development' => 0,
	'testing' => 0,
	'production' => 0
];
$lang['CONF_SIGIN_RECOVER_PASS'] = 'OFF';
$lang['CONF_WIDGET_REST_COUNTRY'] = 'ON';
$lang['CONF_AGENT_INFO'] = 'ON';
$lang['CONF_VALID_POSITION'] = 'right';
$lang['CONF_SINGLE_SIGN_ON'] = 'loginFull';
$lang['CONF_SIGNIN_IMG'] = 'ON';
$lang['CONF_SIGNIN_WIDGET_CONTACT'] = 'OFF';
$lang['CONF_PASS_EXPIRED'] = 'OFF';
$lang['CONF_RECOV_PASS'] = 'OFF';
$lang['CONF_FOOTER_NETWORKS'] = 'ON';
$lang['CONF_FOOTER_LOGO'] = 'OFF';
$lang['CONF_UNNA_EXPIRED_DATE'] = 'OFF';
$lang['CONF_UNNA_STARTING_LINE1'] = 'OFF';
$lang['CONF_STARTING_LINE2_REQUIRED'] = 'OFF';
$lang['CONF_UNNA_PASSWORD'] = 'OFF';
$lang['CONF_UNNA_ACCOUNT_NUMBER'] = 'OFF';
$lang['CONF_BULK_AUTHORIZE'] = 'OFF';
$lang['CONF_BULK_SELECT_ALL_AUTH'] = 'OFF';
$lang['CONF_NIT_INPUT_BOOL'] = 'OFF';
$lang['CONF_CLOSING_BALANCE_BOOL'] = 'OFF';
$lang['CONF_LAST_UPDATE_COLUMN']= 'OFF';
$lang['CONF_SETTINGS_BRANCHES'] = 'OFF';
$lang['CONF_SETTINGS_TELEPHONES'] = 'OFF';
$lang['CONF_SETTINGS_CHANGE_PASSWORD'] = 'OFF';
$lang['CONF_SETT_STYLE_SKIN'] = 'col-4';
$lang['CONF_DNI_COLUMN']= 'OFF';
$lang['CONF_TERMINAL_COLUMN']= 'OFF';
$lang['CONF_SECUENCE_COLUMN']= 'OFF';
$lang['CONF_STATUS_ACCOUNT_ADD_COLUMNS'] = 'OFF';
$lang['CONF_INPUT_UPPERCASE']= 'ON';
$lang['CONF_REFERENCE']= 'ON';
$lang['CONF_REMOTE_AUTH'] = 'ON';
$lang['CONF_AUTH_LIST'] = [
	'CREDIT_TO_CARD', 'DEBIT_TO_CARD', 'LOCK_TYPES', 'TEMPORARY_UNLOCK', 'CARD_ASSIGNMENT', 'CARD_CANCELLATION'
];
$lang['CONF_AUTH_VALIDATE'] = ['LOCK_TYPES', 'CREDIT_TO_CARD', 'CARD_ASSIGNMENT', 'UPDATE_DATA', 'CARD_CANCELLATION'];
$lang['CONF_AUTH_LOADING_URL'] = [
	'development' => [
		'ANY' =>	'',
		'BEM' =>	'https://15.128.26.90/nbem03/images/loader.gif',
		'BEP' =>	'https://15.128.26.90/nbem03/images/loader.gif'
	],
	'testing' => [
		'ANY' =>	'',
		'BEM' =>	'https://15.128.26.90/nbem03/images/loader.gif',
		'BEP' =>	'https://15.128.26.90/nbem03/images/loader.gif'
	],
	'production' => [
		'ANY' =>	'',
		'BEM' =>	'https://nbem.banorte.com/nbxi/images/loader.gif',
		'BEP' =>	'https://nixe.ixe.com.mx/nbxi/images/loader.gif'
	]
];
$lang['CONF_AUTH_URL'] = [
	'development' => [
		'ANY' => '',
		'BEM' => 'https://15.128.26.90/nbem03/AutorizacionRemota.aspx',
		'BEP' => 'https://15.128.26.105/nbxi03/AutorizacionRemota.aspx'
	],
	'testing' => [
		'ANY' => '',
		'BEM' => 'https://15.128.26.90/nbem03/AutorizacionRemota.aspx',
		'BEP' => 'https://15.128.26.105/nbxi03/AutorizacionRemota.aspx'
	],
	'production' => [
		'ANY' => '',
		'BEM' => 'https://nbem.banorte.com/nbxi/autorizacionremota.aspx',
		'BEP' => 'https://nixe.ixe.com.mx/nbxi/autorizacionremota.aspx'
	]
];
$lang['CONF_SECTION_COMMISSION'] = 'OFF';
$lang['CONF_BALANCE_ACC_CONCENTRATOR'] = 'ON';
$lang['CONF_TABLE_AFFILIATED_COLUMNS'] = 'OFF';
$lang['CONF_FOTTER_NETWORKS_LINK'] = [
	'facebook' => 'https://www.facebook.com/banorte',
	'twitter' => 'https://www.twitter.com/Banorte_mx',
	'youtube' => 'https://www.youtube.com/user/banortemovil',
	'instagram' => 'https://www.instagram.com/banorte_mx',
];
$lang['CONF_VALIDATE_FISCAL_REGISTRY'] = '^["a-z0-9"]{8,9}';
$lang['CONF_VALIDATE_REG_ID_NUMBER'] = '^[a-z0-9]+$';
$lang['CONF_VALIDATE_MINLENGTH'] = 16;
$lang['CONF_PADDING_LOGO'] = 'OFF';
