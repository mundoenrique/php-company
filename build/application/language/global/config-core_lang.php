<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$lang['CONF_MAINTENANCE'] = 'OFF';
$lang['CONF_MAINTENANCE_RC'] = 9997;
$lang['CONF_ACTIVE_RECAPTCHA'] = ACTIVE_RECAPTCHA;
$lang['CONF_KEY_RECAPTCHA'] = '6Lejt6MUAAAAANd7KndpsZ2mRSQXuYHncIxFJDYf';
$lang['CONF_CYPHER_DATA'] = ACTIVE_SAFETY ?? 'ON';
$lang['CONF_VIEW_SUFFIX'] = '-core';
$lang['CONF_RC_DEFAULT'] = -9999;
$lang['CONF_DEFAULT_CODE'] = 4;
$lang['CONF_VALIDATE_CAPTCHA'] = [
	'signIn',
	'login',
	'recoverAccess',
	'recoverPass',
	'passwordRecovery'
];
//SUPPORT IE 11
$lang['CONF_SUPPORT_IE'] = 'OFF';
//AGENT INFO
$lang['CONF_AGENT_INFO'] = 'OFF';
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
//NAVBAR STYLES
$lang['CONF_HEADER_BORDER'] = 'OFF';
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
$lang['CONF_SIGNIN_WELCOME_MSG'] = 'OFF';
$lang['CONF_SIGNIN_IMG'] = 'OFF';
$lang['CONF_SIGNIN_WIDGET_CONTACT'] = 'ON';
$lang['CONF_PASS_EXPIRED'] = 'ON';
$lang['CONF_WIDGET_REST_COUNTRY'] = 'OFF';
$lang['CONF_LONG_TEXT'] = '';
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
$lang['CONF_SUPERINTENDENCY_LOGO'] = 'OFF';
$lang['CONF_FOOTER_LOGO'] = 'ON';
$lang['CONF_FOOTER_INFO'] = 'ON';
//CONFIRM BULK
$lang['CONF_CONFIRM_MSG'] = 'OFF';
//AUTHORIZE BULK LIST
$lang['CONF_BULK_AUTHORIZE'] = 'ON';
$lang['CONF_BULK_SELECT_ALL_SIGN'] = 'ON';
$lang['CONF_BULK_SELECT_ALL_AUTH'] = 'ON';
$lang['CONF_BULK_AUTH_MSG_SERV'] = 'OFF';
//AUTHORIZE BULK CARD CREATION
$lang['CONF_IMAGE_CLOCK'] = 'OFF';
//HASH PASSWORD
$lang['CONF_HASH_PASS'] = 'ON';
//REPORT CLOSING BALANCE
$lang['CONF_NIT_INPUT_BOOL'] = 'ON';
//SERVICES TRANSFER MASTER ACCOUNT RECHARGE
$lang['CONF_SELECT_TYPE'] = 'OFF';
$lang['CONF_INPUT_PASS'] = 'ON';
$lang['CONF_VALIDATE_PARAMS'] = 'OFF';
//SETTINGS
$lang['CONF_SETT_CONFIG'] = 'ON';
$lang['CONF_SETTINGS_DISCTRICT']= 'OFF';
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
$lang['CONF_SETTINGS_ADDRESS_ENTERPRICE_UPDATE'] = 'OFF';
$lang['CONF_SETTINGS_CONTACT'] = 'ON';
$lang['CONF_SETTINGS_CHANGE_PASSWORD'] = 'ON';
//FILES CONF
$lang['CONF_FILES_GENERAL'] = [];
//DOWNLOAD ICONS
$lang['CONF_PDF_ICON'] = 'icon-pdf.svg';
$lang['CONF_RAR_ICON'] = 'icon-rar.svg';
$lang['CONF_ZIP_ICON'] = 'icon-zip.svg';
$lang['CONF_SETT_ICON'] = 'icon-settings.svg';
$lang['CONF_XLS_ICON'] = 'icon-xls.svg';
//TEXT CONF
$lang['CONF_DOWNLOADS'] = 'Descargas';
$lang['CONF_MANUALS'] = 'Manuales';
$lang['CONF_APPLICATIONS'] = 'Aplicaciones';
$lang['CONF_FILE'] = 'Archivos de gestión %s';
$lang['CONF_CEO_USER_MANUAL'] = 'Manual de Usuario Conexión Empresas Online';
$lang['CONF_GL_USER_MANUAL'] = 'Manual de Usuario Gestor de Lotes';
$lang['CONF_RESTAR_USERNAME'] = 'OFF';
//DATEPICKER
$lang['CONF_MAX_CONSULT_MONTH'] = 3;
$lang['CONF_MIN_CONSULT_YEAR'] = 2000;
$lang['CONF_PICKER_FIRSTDATE'] = 1;
$lang['CONF_PICKER_ISRLT'] = FALSE;
$lang['CONF_PICKER_SHOWMONTHAFTERYEAR'] = FALSE;
$lang['CONF_PICKER_YEARRANGE'] = '-20:';
$lang['CONF_PICKER_CHANGEMONTH'] = TRUE;
$lang['CONF_PICKER_CHANGEYEAR'] = TRUE;
$lang['CONF_PICKER_SHOWANIM'] = 'slideDown';
//DATATABLE
$lang['CONF_TABLE_SNEXT'] = '»';
$lang['CONF_TABLE_SPREVIOUS'] = '«';
$lang['CONF_MIN_CONSULT_YEAR'] = '-60m';
//UPPER CASE INPUTS
$lang['CONF_INPUT_UPPERCASE']= 'OFF';
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
//MASTER ACCOUNT
$lang['CONF_SECTION_COMMISSION'] = 'ON';
$lang['CONF_BALANCE_ACC_CONCENTRATOR'] = 'OFF';
//BULK UNNAMED AFFILIATED CARDS
$lang['CONF_TABLE_UNNAMED_CARDS'] = 'ON';
$lang['CONF_TABLE_AFFILIATED_COLUMNS'] = 'OFF';
//ICON WARNING SERVICE ORDERS
$lang['CONF_SERVICEORDERS_ICON'] = 'OFF';
//EXTERNAL LINKS
$lang['CONF_NO_LINK'] = 'javascript:';
$lang['CONF_LINK_SIGNIN'] = 'sign-in';
$lang['CONF_LINK_SIGNOUT'] = 'sign-out/';
$lang['CONF_LINK_SIGNOUT_START'] = 'start';
$lang['CONF_LINK_SIGNOUT_END'] = 'end';
$lang['CONF_LINK_BENEFITS'] = 'benefits-inf';
$lang['CONF_LINK_BENEFITS_INF'] = 'benefits';
$lang['CONF_LINK_TERMS'] = 'terms-inf';
$lang['CONF_LINK_RECOVER_ACCESS'] = 'recover-password';
$lang['CONF_LINK_CHANGE_PASS'] = 'change-password';
$lang['CONF_LINK_ENTERPRISES'] = 'enterprises';
$lang['CONF_LINK_PRODUCTS'] = 'products';
$lang['CONF_LINK_PRODUCT_DETAIL'] = 'product-detail';
$lang['CONF_FOTTER_NETWORKS_LINK'] = [
	'facebook' => $lang['CONF_NO_LINK'],
	'twitter' => $lang['CONF_NO_LINK'],
	'youtube' => $lang['CONF_NO_LINK'],
	'instagram' => $lang['CONF_NO_LINK'],
];
$lang['CONF_LINK_USERS_MANAGEMENT'] = 'users-management';//permiso asociado USEREM
$lang['CONF_LINK_USERS_PERMISSIONS'] = 'user-permissions';//permiso asociado USEREM-->CREUSU y USEREM-->ASGPER
$lang['CONF_LINK_BULK_LOAD'] = 'bulk-load';//permiso asociado TEBCAR
$lang['CONF_LINK_BULK_DETAIL'] = 'bulk-detail';//permiso asociado TEBCAR
$lang['CONF_LINK_BULK_CONFIRM'] = 'bulk-confirm';//permiso asociado TEBCAR-->TEBCON
$lang['CONF_LINK_BULK_AUTH'] = 'bulk-authorize';//permiso asociado TEBAUT
$lang['CONF_LINK_BULK_UNNAMED_REQ'] = 'unnamed-request';//permiso asociado TICARG
$lang['CONF_LINK_BULK_UNNAMED_AFFIL'] = 'unnamed-affiliation';//permiso asociado TIINVN
$lang['CONF_LINK_BULK_UNNAMED_DETAIL'] = 'unnmamed-detail';//permiso asociado TIINVN
$lang['CONF_LINK_CALC_SERV_ORDER'] = 'calc-serv-order';//permiso asociado TEBAUT
$lang['CONF_LINK_SERVICE_ORDERS'] = 'service-orders';//permiso asociado TEBORS
$lang['CONF_LINK_INQUIRY_BULK_DETAIL'] = 'inquiry-bulk-detail';//permiso asociado TEBAUT || TEBORS
$lang['CONF_LINK_TRANSF_MASTER_ACCOUNT'] = 'transf-master-account';//permiso asociado TRAMAE
$lang['CONF_LINK_CARDS_INQUIRY'] = 'cards-inquiry';//permiso asociado COPELO
$lang['CONF_LINK_TRANSACTIONAL_LIMITS'] = 'transactional-limits';//permiso asociado LIMTRX
$lang['CONF_LINK_COMMERCIAL_TWIRLS'] = 'commercial-twirls';//permiso asociado GIRCOM
$lang['CONF_LINK_DOWNLOAD_FILES'] = 'download-files';//permiso asociado TEBORS
$lang['CONF_LINK_ACCOUNT_STATUS'] = 'account-status';//permiso asociado REPEDO
$lang['CONF_LINK_REPLACEMENT'] = 'replacement';//permiso asociado REPREP
$lang['CONF_LINK_CLOSING_BALANCE'] = 'closing-balance';//permiso asociado REPSAL
$lang['CONF_LINK_RECHARGE_MADE'] = 'recharge-made';//permiso asociado REPPRO
$lang['CONF_LINK_ISSUED_CARDS'] = 'issued-cards';//permiso asociado REPTAR
$lang['CONF_LINK_CATEGORY_EXPENSE'] = 'category-expense';//permiso asociado REPCAT
$lang['CONF_LINK_STATUS_BULK'] = 'status-bulk';//permiso asociado REPLOT
$lang['CONF_LINK_CARD_HOLDERS'] = 'card-holders';//permiso asociado TEBTHA
$lang['CONF_LINK_REPORTS'] = 'reports';//permiso asociado REPALL
$lang['CONF_LINK_MASTER_ACCOUNT'] = 'master-account';//permiso asociado REPCON
$lang['CONF_LINK_USER_ACTIVITY'] = 'user-activity';//permiso asociado REPUSU
$lang['CONF_LINK_SETTING'] = 'setting';
$lang['CONF_LINK_STATUS_MASTER_ACCOUNT'] = 'status-master-account';//permiso asociado REPECT
//INTERNAL LINKS
$lang['CONF_LINK_SUGGESTION'] = 'suggestion';
$lang['CONF_LINK_RATES'] = 'rates';
$lang['CONF_LINK_UPDATE_ADDRESS_ENTERPRICE'] = 'changeTelephones';
//LANGUAGE
$lang['CONF_BTN_LANG'] = 'OFF';
$lang['CONF_MENU_CIRCLE'] = 'OFF';
//FRANCHISE LOGO
$lang['CONF_FRANCHISE_LOGO'] = 'ON';
//DOWNLOAD TIME FOR FILES
$lang['CONF_TIME_DOWNLOAD_FILE'] = '7000';
//STATUS REJECTED SERVICE ORDERS
$lang['CONF_STATUS_REJECTED'] = '5';
//UPLOAD FILE
$lang['CONF_UPLOAD_SFTP(0)'] = 'CURLE_PROCESS_OK';
$lang['CONF_UPLOAD_SFTP(1)'] = 'CURLE_UNSUPPORTED_PROTOCOL';
$lang['CONF_UPLOAD_SFTP(2)'] = 'CURLE_FAILED_INIT';
$lang['CONF_UPLOAD_SFTP(3)'] = 'CURLE_URL_MALFORMAT';
$lang['CONF_UPLOAD_SFTP(4)'] = 'CURLE_URL_MALFORMAT_USER';
$lang['CONF_UPLOAD_SFTP(5)'] = 'CURLE_COULDNT_RESOLVE_PROXY';
$lang['CONF_UPLOAD_SFTP(6)'] = 'CURLE_COULDNT_RESOLVE_HOST';
$lang['CONF_UPLOAD_SFTP(7)'] = 'CURLE_COULDNT_CONNECT';
$lang['CONF_UPLOAD_SFTP(8)'] = 'CURLE_FTP_WEIRD_SERVER_REPLY';
$lang['CONF_UPLOAD_SFTP(9)'] = 'CURLE_REMOTE_ACCESS_DENIED';
$lang['CONF_UPLOAD_SFTP(11)'] = 'CURLE_FTP_WEIRD_PASS_REPLY';
$lang['CONF_UPLOAD_SFTP(13)'] = 'CURLE_FTP_WEIRD_PASV_REPLY';
$lang['CONF_UPLOAD_SFTP(14)'] = 'CURLE_FTP_WEIRD_227_FORMAT';
$lang['CONF_UPLOAD_SFTP(15)'] = 'CURLE_FTP_CANT_GET_HOST';
$lang['CONF_UPLOAD_SFTP(17)'] = 'CURLE_FTP_COULDNT_SET_TYPE';
$lang['CONF_UPLOAD_SFTP(18)'] = 'CURLE_PARTIAL_FILE';
$lang['CONF_UPLOAD_SFTP(19)'] = 'CURLE_FTP_COULDNT_RETR_FILE';
$lang['CONF_UPLOAD_SFTP(21)'] = 'CURLE_QUOTE_ERROR';
$lang['CONF_UPLOAD_SFTP(22)'] = 'CURLE_HTTP_RETURNED_ERROR';
$lang['CONF_UPLOAD_SFTP(23)'] = 'CURLE_WRITE_ERROR';
$lang['CONF_UPLOAD_SFTP(25)'] = 'CURLE_UPLOAD_FAILED';
$lang['CONF_UPLOAD_SFTP(26)'] = 'CURLE_READ_ERROR';
$lang['CONF_UPLOAD_SFTP(27)'] = 'CURLE_OUT_OF_MEMORY';
$lang['CONF_UPLOAD_SFTP(28)'] = 'CURLE_OPERATION_TIMEDOUT';
$lang['CONF_UPLOAD_SFTP(30)'] = 'CURLE_FTP_PORT_FAILED';
$lang['CONF_UPLOAD_SFTP(31)'] = 'CURLE_FTP_COULDNT_USE_REST';
$lang['CONF_UPLOAD_SFTP(33)'] = 'CURLE_RANGE_ERROR';
$lang['CONF_UPLOAD_SFTP(34)'] = 'CURLE_HTTP_POST_ERROR';
$lang['CONF_UPLOAD_SFTP(35)'] = 'CURLE_SSL_CONNECT_ERROR';
$lang['CONF_UPLOAD_SFTP(36)'] = 'CURLE_BAD_DOWNLOAD_RESUME';
$lang['CONF_UPLOAD_SFTP(37)'] = 'CURLE_FILE_COULDNT_READ_FILE';
$lang['CONF_UPLOAD_SFTP(38)'] = 'CURLE_LDAP_CANNOT_BIND';
$lang['CONF_UPLOAD_SFTP(39)'] = 'CURLE_LDAP_SEARCH_FAILED';
$lang['CONF_UPLOAD_SFTP(41)'] = 'CURLE_FUNCTION_NOT_FOUND';
$lang['CONF_UPLOAD_SFTP(42)'] = 'CURLE_ABORTED_BY_CALLBACK';
$lang['CONF_UPLOAD_SFTP(43)'] = 'CURLE_BAD_FUNCTION_ARGUMENT';
$lang['CONF_UPLOAD_SFTP(45)'] = 'CURLE_INTERFACE_FAILED';
$lang['CONF_UPLOAD_SFTP(47)'] = 'CURLE_TOO_MANY_REDIRECTS';
$lang['CONF_UPLOAD_SFTP(48)'] = 'CURLE_UNKNOWN_TELNET_OPTION';
$lang['CONF_UPLOAD_SFTP(49)'] = 'CURLE_TELNET_OPTION_SYNTAX';
$lang['CONF_UPLOAD_SFTP(51)'] = 'CURLE_PEER_FAILED_VERIFICATION';
$lang['CONF_UPLOAD_SFTP(52)'] = 'CURLE_GOT_NOTHING';
$lang['CONF_UPLOAD_SFTP(53)'] = 'CURLE_SSL_ENGINE_NOTFOUND';
$lang['CONF_UPLOAD_SFTP(54)'] = 'CURLE_SSL_ENGINE_SETFAILED';
$lang['CONF_UPLOAD_SFTP(55)'] = 'CURLE_SEND_ERROR';
$lang['CONF_UPLOAD_SFTP(56)'] = 'CURLE_RECV_ERROR';
$lang['CONF_UPLOAD_SFTP(58)'] = 'CURLE_SSL_CERTPROBLEM';
$lang['CONF_UPLOAD_SFTP(59)'] = 'CURLE_SSL_CIPHER';
$lang['CONF_UPLOAD_SFTP(60)'] = 'CURLE_SSL_CACERT';
$lang['CONF_UPLOAD_SFTP(61)'] = 'CURLE_BAD_CONTENT_ENCODING';
$lang['CONF_UPLOAD_SFTP(62)'] = 'CURLE_LDAP_INVALID_URL';
$lang['CONF_UPLOAD_SFTP(63)'] = 'CURLE_FILESIZE_EXCEEDED';
$lang['CONF_UPLOAD_SFTP(64)'] = 'CURLE_USE_SSL_FAILED';
$lang['CONF_UPLOAD_SFTP(65)'] = 'CURLE_SEND_FAIL_REWIND';
$lang['CONF_UPLOAD_SFTP(66)'] = 'CURLE_SSL_ENGINE_INITFAILED';
$lang['CONF_UPLOAD_SFTP(67)'] = 'CURLE_LOGIN_DENIED';
$lang['CONF_UPLOAD_SFTP(68)'] = 'CURLE_TFTP_NOTFOUND';
$lang['CONF_UPLOAD_SFTP(69)'] = 'CURLE_TFTP_PERM';
$lang['CONF_UPLOAD_SFTP(70)'] = 'CURLE_REMOTE_DISK_FULL';
$lang['CONF_UPLOAD_SFTP(71)'] = 'CURLE_TFTP_ILLEGAL';
$lang['CONF_UPLOAD_SFTP(72)'] = 'CURLE_TFTP_UNKNOWNID';
$lang['CONF_UPLOAD_SFTP(73)'] = 'CURLE_REMOTE_FILE_EXISTS';
$lang['CONF_UPLOAD_SFTP(74)'] = 'CURLE_TFTP_NOSUCHUSER';
$lang['CONF_UPLOAD_SFTP(75)'] = 'CURLE_CONV_FAILED';
$lang['CONF_UPLOAD_SFTP(76)'] = 'CURLE_CONV_REQD';
$lang['CONF_UPLOAD_SFTP(77)'] = 'CURLE_SSL_CACERT_BADFILE';
$lang['CONF_UPLOAD_SFTP(78)'] = 'CURLE_REMOTE_FILE_NOT_FOUND';
$lang['CONF_UPLOAD_SFTP(79)'] = 'CURLE_SSH';
$lang['CONF_UPLOAD_SFTP(80)'] = 'CURLE_SSL_SHUTDOWN_FAILED';
$lang['CONF_UPLOAD_SFTP(81)'] = 'CURLE_AGAIN';
$lang['CONF_UPLOAD_SFTP(82)'] = 'CURLE_SSL_CRL_BADFILE';
$lang['CONF_UPLOAD_SFTP(83)'] = 'CURLE_SSL_ISSUER_ERROR';
$lang['CONF_UPLOAD_SFTP(84)'] = 'CURLE_FTP_PRET_FAILED';
$lang['CONF_UPLOAD_SFTP(84)'] = 'CURLE_FTP_PRET_FAILED';
$lang['CONF_UPLOAD_SFTP(85)'] = 'CURLE_RTSP_CSEQ_ERROR';
$lang['CONF_UPLOAD_SFTP(86)'] = 'CURLE_RTSP_SESSION_ERROR';
$lang['CONF_UPLOAD_SFTP(87)'] = 'CURLE_FTP_BAD_FILE_LIST';
$lang['CONF_UPLOAD_SFTP(88)'] = 'CURLE_CHUNK_FAILED';
//GENERAL LANGUAGE
$lang['CONF_CURRENCY'] = '$';
$lang['CONF_DECIMAL'] = '.';
$lang['CONF_THOUSANDS'] = ',';
$lang['CONF_BROWSER_GOOGLE_CHROME'] = 'Google Chrome';
$lang['CONF_BROWSER_GOOGLE_CHROME_VERSION'] = 'Version 48+';
$lang['CONF_BROWSER_MOZILLA_FIREFOX'] = 'Mozilla Firefox';
$lang['CONF_BROWSER_MOZILLA_FIREFOX_VERSION'] = 'Version 30+';
$lang['CONF_BROWSER_APPLE_SAFARI'] = 'Apple Safari';
$lang['CONF_BROWSER_APPLE_SAFARI_VERSION'] = 'Version 10+';
$lang['CONF_BROWSER_MICROSOFT_EDGE'] = 'Microsoft Edge';
$lang['CONF_BROWSER_MICROSOFT_EDGE_VERSION'] = 'Version 14+';
$lang['CONF_BROWSER_INTERNET_EXPLORER'] = 'Internet Explorer';
$lang['CONF_BROWSER_INTERNET_EXPLORER_VERSION'] = 'Version 11+';
$lang['CONF_MIN_WIDTH_OTP'] = '480';
$lang['CONF_POSTMY_OTP'] = 'center top+160';
//VALIDATIONS
$lang['CONF_VALIDATE_FISCAL_REGISTRY'] = '^(10|15|16|17|20)[\d]{8}[\d]{1}$';
$lang['CONF_VALIDATE_RECHAR_REGEX_DESC'] = '^[a-z0-9ñáéíóú,.:()]+$';
$lang['CONF_FILES_EXTENSION'] = 'xls|xlsx|txt';
$lang['CONF_MAX_FILE_SIZE'] = '2048';
$lang['CONF_VALIDATE_REG_ID_NUMBER'] = '^[0-9]+$';
$lang['CONF_VALIDATE_MINLENGTH'] = 1;
