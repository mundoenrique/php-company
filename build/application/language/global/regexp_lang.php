<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['REGEX_USER_NAME'] = '^([\w\.\-\+&ñÑ\s]+)+$';
$lang['REGEX_USER_NAME_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_USER_NAME'] . '/i]|required';
$lang['REGEX_PASSWORD'] = '^([\w!@\*\-\?¡¿+\/.,#ñÑ]+)+$';
$regexPass = ACTIVE_SAFETY ? '^([a-zA-Z0-9=]+)+$' : $lang['REGEX_PASSWORD'];
$lang['REGEX_PASSWORD_SERVER'] = 'trim|regex_match[/' . $regexPass . '/i]|required';
$lang['REGEX_ALPHA_NUM'] = '^[a-z0-9]+$';
$lang['REGEX_ALPHA_NUM_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_ALPHA_NUM'] . '/i]';
$lang['REGEX_SAVE_IP'] = '(' . TRUE . '|' . FALSE . ')';
$lang['REGEX_SAVE_IP_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_SAVE_IP'] . '/]';
$lang['REGEX_CHANGE_LANG'] = '(es|en)';
$lang['REGEX_CHANGE_LANG_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_CHANGE_LANG'] . '/]|required';



$lang['REGEX_ID_NUMBER'] = '^([0-9]{7,9}$)';
$lang['REGEX_ID_NUMBER_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_ID_NUMBER'] . '/]|required';
$lang['REGEX_EMAIL'] = '^([a-zA-Z0-9]+[a-zA-Z0-9_.+-]*)+\@(([a-zA-Z0-9_-])+\.)+([a-zA-Z0-9]{2,4})+$';
$lang['REGEX_EMAIL_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_EMAIL'] . '/]|required';
$lang['REGEX_PHONE'] = '^([0-9]{6,15}$)';
$lang['REGEX_PHONE_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_PHONE'] . '/]';
$lang['REGEX_ALPHA_STRING'] = '^([\wñáéíóúüÀÈÌÒÙÜ\s]{2,50}$)';
$lang['REGEX_ALPHA_STRING_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_ALPHA_STRING'] . '/i]|required';


$lang['REGEX_NUMERIC'] = '^[0-9]+$';
