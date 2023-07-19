<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$lang['CONF_VALIDATE_FISCAL_REGISTRY'] = '^([VEJPGvejpg]{1})-([0-9]{8})-([0-9]{1}$)';
$lang['CONF_FILES_EXTENSION'] = 'txt';
$lang['CONF_BULK_AUTHORIZE'] = 'OFF';
$lang['CONF_BULK_TYPE_SERVICE_ORDER'] = '1';
$lang['CONF_MAINT_NOTIF'] = strtotime(date("d-m-Y H:i:00", time())) < strtotime("23-07-2023 14:00:00") ? 'ON' : 'OFF';
$lang['CONF_BTN_LANG'] = 'OFF';
$lang['CONF_MENU_CIRCLE'] = 'ON';
$lang['CONF_ISSUED_MONTHLY'] = 'OFF';
$lang['CONF_FOOTER_RATES'] = 'ON';
$lang['CONF_SIGNIN_IMG'] = 'ON';
$lang['CONF_ENTERPRICE_CONTACT'] = 'ON';
