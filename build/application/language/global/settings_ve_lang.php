<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['SETT_VALIDATE_FISCAL_REGISTRY'] = '^([VEJPGvejpg]{1})-([0-9]{8})-([0-9]{1}$)';
$lang['SETT_FILES_EXTENSION'] = 'txt';
$lang['SETT_BULK_AUTHORIZE'] = 'OFF';
$lang['SETT_BULK_TYPE_SERVICE_ORDER'] = '1';
$lang['SETT_MAINT_NOTIF'] = strtotime(date("d-m-Y H:i:00", time())) < strtotime("23-07-2023 14:00:00") ? 'ON' : 'OFF';
$lang['SETT_MENU_CIRCLE'] = 'ON';
$lang['SETT_ISSUED_MONTHLY'] = 'OFF';
$lang['SETT_FOOTER_RATES'] = 'ON';
$lang['SETT_SIGNIN_IMG'] = 'ON';
$lang['SETT_BULK_REPROCESS'] = 'ON';
$lang['SETT_ENTERPRICE_CONTACT'] = 'ON';
$lang['SETT_REPLACE_ISSUE_DATE'] = 'OFF';
$lang['SETT_REPLACE_BULK_ID'] = 'ON';
$lang['SETT_REPLACE_SERV_ORDER'] = 'ON';
$lang['SETT_REPLACE_INV_NUMBER'] = 'ON';
