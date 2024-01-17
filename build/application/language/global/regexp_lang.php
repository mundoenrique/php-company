<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['REGEX_NUMERIC'] = '^[0-9]+$';
$lang['REGEX_ALPHA_NUM'] = '^[a-z0-9]+$';
$lang['REGEX_INT_REQUIRED'] = 'trim|integer|required';
$lang['REGEX_USER_NAME'] = '^([\w\.\-\+&ñÑ\s]+)+$';
$lang['REGEX_USER_NAME_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_USER_NAME'] . '/i]|required';
$lang['REGEX_PASSWORD'] = '^([\w!@\*\-\?¡¿+\/.,#ñÑ]+)+$';
$regexPass = ACTIVE_SAFETY ? '^([a-zA-Z0-9=]+)+$' : $lang['REGEX_PASSWORD'];
$lang['REGEX_PASSWORD_SERVER'] = 'trim|regex_match[/' . $regexPass . '/i]|required';
$lang['REGEX_ALPHA_NUM_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_ALPHA_NUM'] . '/i]';
$lang['REGEX_SAVE_IP'] = '(' . TRUE . '|' . FALSE . ')';
$lang['REGEX_SAVE_IP_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_SAVE_IP'] . '/]';
$lang['REGEX_CHANGE_LANG'] = '(es|en)';
$lang['REGEX_CHANGE_LANG_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_CHANGE_LANG'] . '/]|required';
$lang['REGEX_SESS_ID_SERVER'] = 'trim';
$lang['REGEX_KEY_SERVER'] = 'trim';
$lang['REGEX_SERVICE_ID_SERVER'] = 'trim';
$lang['REGEX_CHANNEL_SERVER'] = 'trim';
$lang['REGEX_IP_SERVER'] = 'trim';
$lang['REGEX_OPC_SERVER'] = 'trim';
$lang['REGEX_ENTERPRISE_CODE'] = '^([a-z0-9\-\.])+$';
$lang['REGEX_ENTERPRISE_CODE_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_ENTERPRISE_CODE'] . '/i]|required';
$lang['REGEX_ENTERPRISE_NAME'] = '^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$';
$lang['REGEX_ENTERPRISE_NAME_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_ENTERPRISE_NAME'] . '/i]';
$lang['REGEX_PRODUCT_CODE'] = '^([a-z0-9])+$';
$lang['REGEX_PRODUCT_CODE_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_PRODUCT_CODE'] . '/i]|required';
$lang['REGEX_PRODUCT_NAME'] = '^([\wñÑáéíóúÑÁÉÍÓÚ\(\) ]+)+$';
$lang['REGEX_PRODUCT_NAME_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_PRODUCT_NAME'] . '/i]';
$lang['REGEX_REPLACE_TYPE'] = '(01|02)';
$lang['REGEX_REPLACE_TYPE_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_REPLACE_TYPE'] . '/]|required';
$lang['REGEX_DATE_DMY'] = '^([0-9\/])+$';
$lang['REGEX_DATE_DMY_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_DATE_DMY'] . '/]|required';
$lang['REGEX_REPORT_TYPE'] = '(list|xls|pdf)';
$lang['REGEX_REPORT_TYPE_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_REPORT_TYPE'] . '/]|required';
$lang['REGEX_DRAW_SERVER'] = $lang['REGEX_INT_REQUIRED'];
$lang['REGEX_START_SERVER'] = $lang['REGEX_INT_REQUIRED'];
$lang['REGEX_LENGHT_SERVER'] = $lang['REGEX_INT_REQUIRED'];
$lang['REGEX_DOCUMENT_ID'] = $lang['REGEX_NUMERIC'];
$lang['REGEX_DOCUMENT_ID_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_DOCUMENT_ID'] . '/i]';



$lang['REGEX_ID_NUMBER'] = '^([0-9]{7,9}$)';
$lang['REGEX_ID_NUMBER_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_ID_NUMBER'] . '/]|required';
$lang['REGEX_EMAIL'] = '^([a-zA-Z0-9]+[a-zA-Z0-9_.+-]*)+\@(([a-zA-Z0-9_-])+\.)+([a-zA-Z0-9]{2,4})+$';
$lang['REGEX_EMAIL_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_EMAIL'] . '/]|required';
$lang['REGEX_PHONE'] = '^([0-9]{6,15}$)';
$lang['REGEX_PHONE_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_PHONE'] . '/]';
$lang['REGEX_ALPHA_STRING'] = '^([\wñáéíóúüÀÈÌÒÙÜ\s]{2,50}$)';
$lang['REGEX_ALPHA_STRING_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_ALPHA_STRING'] . '/i]|required';
