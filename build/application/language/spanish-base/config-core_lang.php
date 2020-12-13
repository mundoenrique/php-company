<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$lang['CONFIG_MAINTENANCE'] = 'OFF';
$lang['CONF_VIEW_SUFFIX'] = '-core';
$lang['CONF_VALIDATE_CAPTCHA'] = ['signIn'];
//SUPPORT IE 11
$lang['CONF_SUPPORT_IE'] = 'OFF';
//AGENT INFO
$lang['CONF_AGEN_INFO'] = 'OFF';
//SCORE RECAPTCHA
$lang['CONF_SCORE_CAPTCHA'] = [
	'development' => 0,
	'testing' => 0.2,
	'production' => 0.5
];
//VALIDATE FORMS
$lang['CONF_VALID_ERROR'] = 'has-error';
$lang['CONF_VALID_VALID'] = 'has-success';
$lang['CONF_VALID_SUCCESS'] = ' ';
$lang['CONF_VALID_IGNORE'] = '.ignore';
$lang['CONF_VALID_ELEMENT'] = 'div';
$lang['CONF_VALID_INVALID_USER'] = 'invalid-user';
$lang['CONF_VALID_INACTIVE_USER'] = 'inactive-user';
$lang['CONF_VALID_POSITION'] = 'left';
//MODAL STYLES
$lang['CONF_MODAL_WIDTH'] = 370;
//ICONS MODALS
$lang['CONF_ICON'] = 'ui-icon mt-0';
$lang['CONF_ICON_SUCCESS'] = 'ui-icon-circle-check';
$lang['CONF_ICON_INFO'] = 'ui-icon-info';
$lang['CONF_ICON_WARNING'] = 'ui-icon-alert';
$lang['CONF_ICON_DANGER'] = 'ui-icon-closethick';
//SINGLESIGNON
$lang['CONF_SINGLE_SIGN_ON'] = 'userByToken';
//SIGNIN
$lang['CONF_SIGIN_RECOVER_PASS'] = 'ON';
$lang['CONF_SIGNIN_IMG'] = 'OFF';
$lang['CONF_SIGNIN_WIDGET_CONTACT'] = 'ON';
$lang['CONFIG_PASS_EXPIRED'] = 'ON';
//RECOVER ACCESS
$lang['CONF_RECOV_PASS'] = 'ON';
$lang['CONF_RECOV_ACCESS'] = 'OFF';
//BENEFITS
$lang['CONF_BENEFITS'] = 'ON';
//LOAD BULK
$lang['CONF_BULK_LOAD'] = 'ON';
$lang['CONF_BULK_BRANCHOFFICE'] = 'OFF';
$lang['CONF_BULK_AUTH'] = 'ON';
//LOAD BULK
$lang['CONF_BULK_REPROCESS'] = 'OFF';
//REQUEST UNNAMED
$lang['CONF_UNNA_STARTING_LINE1'] = 'ON';
$lang['CONF_UNNA_STARTING_LINE2'] = 'ON';
$lang['CONF_STARTING_LINE1_REQUIRED'] = 'ON';
$lang['CONF_STARTING_LINE2_REQUIRED'] = 'ON';
$lang['CONF_UNNA_PASSWORD'] = 'ON';
$lang['CONF_UNNA_BRANCHOFFICE'] = 'ON';
$lang['CONF_UNNA_EXPIRED_DATE'] = 'ON';
//DETAIL UNNAMED
$lang['CONF_UNNA_ACCOUNT_NUMBER'] = 'ON';
//CALCULATE SERVICE ORDER
$lang['CONF_SERVICE_ORDER_CANCEL'] = 'ON';
$lang['CONF_SERVICE_ORDER_OTP'] = 'OFF';
//FOOTER
$lang['CONF_FOOTER_NETWORKS'] = 'OFF';
$lang['CONF_FOOTER_LOGO'] = 'ON';
//AUTHORIZE BULK LIST
$lang['CONF_BULK_AUTHORIZE'] = 'ON';
$lang['CONF_BULK_SELECT_ALL_SIGN'] = 'ON';
$lang['CONF_BULK_SELECT_ALL_AUTH'] = 'ON';
$lang['CONF_BULK_AUTH_MSG_SERV'] = 'OFF';
//HASH PASSWORD
$lang['CONF_HASH_PASS'] = 'ON';
//REPORT CLOSING BALANCE
$lang['CONF_NIT_INPUT_BOOL'] = 'ON';
//SERVICES TRANSFER MASTER ACCOUNT
$lang['CONF_SELECT_AMOUNT'] = 'OFF';
//SETTINGS
$lang['CONF_SETT_CONFIG'] = 'ON';
//INPUT CARDS INQUIRY
$lang['CONF_INQUIRY_DOCTYPE'] = 'OFF';
$lang['CONF_CARDS_INQUIRY_ISSUE_STATUS'] = 'ON';
//STYLE FORM
$lang['CONF_SETT_STYLE_SKIN'] = 'col-3';
//CLOSING BALANCE
$lang['CONF_CLOSING_BALANCE_BOOL'] = 'ON';
$lang['CONF_CARD_COLUMN']= 'ON';
$lang['CONF_NAME_COLUMN']= 'ON';
$lang['CONF_ID_COLUMN']= 'ON';
$lang['CONF_BALANCE_COLUMN']= 'ON';
$lang['CONF_LAST_UPDATE_COLUMN']= 'ON';
//STATUS ACCOUNT
$lang['CONF_CARD_COLUMN']= 'ON';
$lang['CONF_DATE_COLUMN']= 'ON';
$lang['CONF_DNI_COLUMN']= 'ON';
$lang['CONF_TERMINAL_COLUMN']= 'ON';
$lang['CONF_SECUENCE_COLUMN']= 'ON';
$lang['CONF_REFERENCE_COLUMN']= 'ON';
$lang['CONF_DESCRIPTION_COLUMN']= 'ON';
$lang['CONF_DEBIT_COLUMN']= 'ON';
$lang['CONF_CREDIT_COLUMN']= 'ON';
$lang['CONF_STATUS_ACCOUNT_ADD_COLUMNS'] = 'ON';
//CARDHOLDERS
$lang['CONF_CARD_NUMBER_COLUMN']= 'OFF';
//SETTINGS
$lang['CONF_SETTINGS_USER'] = 'ON';
$lang['CONF_SETTINGS_EMAIL_UPDATE'] = 'ON';
$lang['CONF_SETTINGS_ENTERPRISE'] = 'ON';
$lang['CONF_SETTINGS_BRANCHES'] = 'ON';
$lang['CONF_SETTINGS_DOWNLOADS'] = 'ON';
$lang['CONF_SETTINGS_TELEPHONES'] = 'ON';
$lang['CONF_SETTINGS_PHONES_UPDATE'] = 'ON';
$lang['CONF_SETTINGS_CONTACT'] = 'ON';
$lang['CONF_SETTINGS_CHANGE_PASSWORD'] = 'ON';

//FILES CONF
$lang['CONF_MANUAL_FILE'] = [];
$lang['CONF_APPS_FILE'] = [];
$lang['CONF_APPS_DOWNLOAD'] = [];
$lang['CONF_FILES_MANAGMENT'] = [];
$lang['CONF_MP4_VIDEO'] = [];
//DOWNLOAD ICONS
$lang['CONF_PDF_ICON'] = 'icon-pdf.svg';
$lang['CONF_RAR_ICON'] = 'icon-rar.svg';
$lang['CONF_ZIP_ICON'] = 'icon-zip.svg';
$lang['CONF_SETT_ICON'] = 'icon-settings.svg';
//TEXT CONF
$lang['CONF_DOWNLOADS'] = 'Descargas';
$lang['CONF_MANUALS'] = 'Manuales';
$lang['CONF_APPLICATIONS'] = 'Aplicaciones';
$lang['CONF_FILE'] = 'Archivos de gestión Conexión Empresas Online';
$lang['CONF_CEO_USER_MANUAL'] = 'Manual de Usuario Conexión Empresas Online';
$lang['CONF_GL_USER_MANUAL'] = 'Manual de Usuario Gestor de Lotes';
//MODAL OTP
$lang['MODAL_OTP'] = 'OFF';
$lang['CONF_RESTAR_USERNAME'] = 'OFF';
//DATEPICKER
$lang['CONF_MAX_CONSULT_MONTH'] = 3;
$lang['CONF_MIN_CONSULT_YEAR'] = 2000;
//UPPER CASE INPUTS
$lang['CONF_INPUT_UPPERCASE']= 'ON';
//REMOTE AUTHORIZATIONS
$lang['CONF_REMOTE_AUTH'] = 'OFF';
$lang['CONF_AUTH_LIST'] = [];
$lang['CONF_AUTH_VALIDATE'] = [];
$lang['CONF_AUTH_LOADING_URL'] = [
	'development' => [
		'ANY' => ''
	],
];
$lang['CONF_AUTH_URL'] = [
	'development' => [
		'ANY' => ''
	],
	'testing' => [
		'ANY' => ''
	],
	'production' => [
		'ANY' => ''
	]
];
//USER ACTIVITY
$lang['CONF_USER_ACTIVITY'] = 'ON';
$lang['CONF_USERS_ACTIVITY'] = 'OFF';
