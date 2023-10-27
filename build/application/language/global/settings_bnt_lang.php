<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['SETT_SIGIN_RECOVER_PASS'] = 'OFF';
$lang['SETT_WIDGET_REST_COUNTRY'] = 'ON';
$lang['SETT_AGENT_INFO'] = 'ON';
$lang['SETT_VALID_POSITION'] = 'right';
$lang['SETT_SINGLE_SIGN_ON'] = 'loginFull';
$lang['SETT_SIGNIN_IMG'] = 'ON';
$lang['SETT_SIGNIN_WIDGET_CONTACT'] = 'OFF';
$lang['SETT_PASS_EXPIRED'] = 'OFF';
$lang['SETT_RECOV_PASS'] = 'OFF';
$lang['SETT_FOOTER_NETWORKS'] = 'ON';
$lang['SETT_FOOTER_LOGO'] = 'OFF';
$lang['SETT_UNNA_EXPIRED_DATE'] = 'OFF';
$lang['SETT_UNNA_STARTING_LINE1'] = 'OFF';
$lang['SETT_STARTING_LINE2_REQUIRED'] = 'OFF';
$lang['SETT_UNNA_PASSWORD'] = 'OFF';
$lang['SETT_UNNA_ACCOUNT_NUMBER'] = 'OFF';
$lang['SETT_BULK_AUTHORIZE'] = 'OFF';
$lang['SETT_BULK_SELECT_ALL_AUTH'] = 'OFF';
$lang['SETT_NIT_INPUT_BOOL'] = 'OFF';
$lang['SETT_CLOSING_BALANCE_BOOL'] = 'OFF';
$lang['SETT_LAST_UPDATE_COLUMN'] = 'OFF';
$lang['SETT_SETTINGS_BRANCHES'] = 'OFF';
$lang['SETT_SETTINGS_TELEPHONES'] = 'OFF';
$lang['SETT_SETTINGS_CHANGE_PASSWORD'] = 'OFF';
$lang['SETT_SETT_STYLE_SKIN'] = 'col-4';
$lang['SETT_DNI_COLUMN'] = 'OFF';
$lang['SETT_TERMINAL_COLUMN'] = 'OFF';
$lang['SETT_SECUENCE_COLUMN'] = 'OFF';
$lang['SETT_STATUS_ACCOUNT_ADD_COLUMNS'] = 'OFF';
$lang['SETT_STATUS_MOVEMENT'] = 'ON';
$lang['SETT_INPUT_UPPERCASE'] = 'ON';
$lang['SETT_REFERENCE'] = 'ON';
$lang['SETT_REMOTE_AUTH'] = 'ON';
$lang['SETT_AUTH_LIST'] = [
  'CREDIT_TO_CARD', 'DEBIT_TO_CARD', 'LOCK_TYPES', 'TEMPORARY_UNLOCK', 'CARD_ASSIGNMENT', 'CARD_CANCELLATION'
];
$lang['SETT_AUTH_VALIDATE'] = ['LOCK_TYPES', 'CREDIT_TO_CARD', 'CARD_ASSIGNMENT', 'UPDATE_DATA', 'CARD_CANCELLATION'];
$lang['SETT_AUTH_LOADING_URL'] = [
  'development' => [
    'ANY' =>  '',
    'BEM' =>  'https://15.128.26.90/nbem03/images/loader.gif',
    'BEP' =>  'https://15.128.26.90/nbem03/images/loader.gif'
  ],
  'testing' => [
    'ANY' =>  '',
    'BEM' =>  'https://15.128.26.90/nbem03/images/loader.gif',
    'BEP' =>  'https://15.128.26.90/nbem03/images/loader.gif'
  ],
  'production' => [
    'ANY' =>  '',
    'BEM' =>  'https://nbem.banorte.com/nbxi/images/loader.gif',
    'BEP' =>  'https://nixe.ixe.com.mx/nbxi/images/loader.gif'
  ]
];
$lang['SETT_AUTH_URL'] = [
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
$lang['SETT_SECTION_COMMISSION'] = 'OFF';
$lang['SETT_BALANCE_ACC_CONCENTRATOR'] = 'ON';
$lang['SETT_TABLE_AFFILIATED_COLUMNS'] = 'OFF';
$lang['SETT_FOTTER_NETWORKS_LINK'] = [
  'facebook' => 'https://www.facebook.com/banorte',
  'twitter' => 'https://www.twitter.com/Banorte_mx',
  'youtube' => 'https://www.youtube.com/user/banortemovil',
  'instagram' => 'https://www.instagram.com/banorte_mx',
];
$lang['SETT_VALIDATE_FISCAL_REGISTRY'] = '^["a-z0-9"]{8,9}';
$lang['SETT_VALIDATE_REG_ID_NUMBER'] = '^[a-z0-9]+$';
$lang['SETT_VALIDATE_MINLENGTH'] = 16;
$lang['SETT_PADDING_LOGO'] = 'OFF';

$lang['SETT_SELECT_TYPE'] = 'ON';
$lang['SETT_INPUT_DESCRIPTION'] = 'OFF';
$lang['SETT_SELECT_ACCOUNT'] = 'OFF';
$lang['SETT_INPUT_PASS'] = 'OFF';
$lang['SETT_REDIRECT_TRANSF_MASTER_ACCOUNT'] = 'OFF';
$lang['SETT_VALIDATE_PARAMS'] = 'ON';
$lang['SETT_ACCOUNT_NAME'] = 'ON';
$lang['SETT_FILE_STATUS_ACCOUNT_TXT'] = 'ON';
$lang['SETT_FILE_STATUS_ACCOUNT_PDF'] = 'ON';
$lang['SETT_FILE_MASTER_ACCOUNT_TXT'] = 'ON';
$lang['SETT_FILE_CLOSE_BALANCE_TXT'] = 'ON';
$lang['SETT_FILE_CLOSE_BALANCE_PDF'] = 'ON';
$lang['SETT_DOWNLOAD_SERVER'] = 'ON';
