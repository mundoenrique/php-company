<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['REGEX_USER_NAME'] = '^([\w\.\-\+&ñÑ\s]+)+$';
$lang['REGEX_USER_NAME_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_USER_NAME'] . '/i]|required';
$lang['REGEX_PASSWORD'] = '^([\w!@\*\-\?¡¿+\/.,#ñÑ]+)+$';
$regexPass = ACTIVE_SAFETY ? '^([a-zA-Z0-9=]+)+$' : $lang['REGEX_PASSWORD'];
$lang['REGEX_PASSWORD_SERVER'] = 'trim|regex_match[/' . $regexPass . '/i]|required';


$lang['REGEX_CHANGE_LANG'] = '(es|en)';
$lang['REGEX_CHANGE_LANG_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_CHANGE_LANG'] . '/]|required';
$lang['REGEX_ONLY_NUMBER'] = '^[0-9]{2,20}$';
