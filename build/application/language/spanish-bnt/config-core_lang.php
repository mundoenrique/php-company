<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//SUPPORT IE 11
$lang['CONF_SUPPORT_IE'] = 'ON';
//SIGNIN
$lang['CONF_SIGIN_RECOVER_PASS'] = 'OFF';
//AGENT INFO
$lang['CONF_AGEN_INFO'] = 'ON';
//VALIDATE FORMS
$lang['CONF_VALID_POSITION'] = 'right';
//SINGLESIGNON
$lang['CONF_SINGLE_SIGN_ON'] = 'loginFull';
//SIGNIN
$lang['CONF_SIGNIN_IMG'] = 'ON';
$lang['CONF_SIGNIN_WIDGET_CONTACT'] = 'OFF';
$lang['CONFIG_PASS_EXPIRED'] = 'OFF';
//RECOVER ACCESS
$lang['CONF_RECOV_PASS'] = 'ON';
$lang['CONF_RECOV_ACCESS'] = 'OFF';
//FOOTER
$lang['CONF_FOOTER_NETWORKS'] = 'ON';
$lang['CONF_FOOTER_LOGO'] = 'OFF';
//REQUEST UNNAMED
$lang['CONF_UNNA_EXPIRED_DATE'] = 'OFF';
$lang['CONF_UNNA_STARTING_LINE1'] = 'OFF';
$lang['CONF_STARTING_LINE2_REQUIRED'] = 'OFF';
$lang['CONF_UNNA_PASSWORD'] = 'OFF';
//DETAIL UNNAMED
$lang['CONF_UNNA_ACCOUNT_NUMBER'] = 'OFF';
//AUTHORIZE BULK LIST
$lang['CONF_BULK_AUTHORIZE'] = 'OFF';
$lang['CONF_BULK_SELECT_ALL_AUTH'] = 'OFF';
//REPORT CLOSING BALANCE
$lang['CONF_NIT_INPUT_BOOL'] = 'OFF';
$lang['CONF_CLOSING_BALANCE_BOOL'] = 'OFF';
$lang['CONF_LAST_UPDATE_COLUMN']= 'OFF';
//SETTINGS
$lang['CONF_SETTINGS_BRANCHES'] = 'OFF';
$lang['CONF_SETTINGS_TELEPHONES'] = 'OFF';
$lang['CONF_SETTINGS_CONTACT'] = 'OFF';
//STYLE FORM
$lang['CONF_SETT_STYLE_SKIN'] = 'col-4';
//FILES CONF
$lang['CONF_MANUAL_FILE'] = [];
$lang['CONF_FILES_MANAGMENT'] = [
  ['lotes.rar', 'Gestión de Lotes']
];
//STATUS ACCOUNT
$lang['CONF_DNI_COLUMN']= 'OFF';
$lang['CONF_TERMINAL_COLUMN']= 'OFF';
$lang['CONF_SECUENCE_COLUMN']= 'OFF';
$lang['CONF_STATUS_ACCOUNT_ADD_COLUMNS'] = 'OFF';
//UPPER CASE INPUTS
$lang['CONF_INPUT_UPPERCASE']= 'OFF';
//REMOTE AUTHORIZATIONS
$lang['CONF_REMOTE_AUTH'] = 'ON';
$lang['CONF_AUTH_LIST'] = [
	'CREDIT_TO_CARD', 'DEBIT_TO_CARD', 'TEMPORARY_LOCK', 'UNLOCK_CARD', 'CARD_ASSIGNMENT'
];
$lang['CONF_AUTH_VALIDATE'] = ['CARD_ASSIGNMENT', 'UPDATE_DATA'];
$lang['CONF_AUTH_URL'] = [
	'development' => [
		'ANY' => '',
		'BEM' => 'https://15.128.26.90/nbem11/AutorizacionRemota.aspx',
		'BEP' => 'https://15.128.26.90/nbem11/AutorizacionRemota.aspx'
	],
	'testing' => [
		'ANY' => '',
		'BEM' => 'https://15.128.26.90/nbem11/AutorizacionRemota.aspx',
		'BEP' => 'https://15.128.26.90/nbem11/AutorizacionRemota.aspx'
	],
	'production' => [
		'ANY' => '',
		'BEM' => 'https://15.128.26.90/nbem11/AutorizacionRemota.aspx',
		'BEP' => 'https://15.128.26.90/nbem11/AutorizacionRemota.aspx'
	]
];
