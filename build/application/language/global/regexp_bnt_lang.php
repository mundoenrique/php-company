<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['REGEX_NUMERIC'] = '^[0-9]+$';
$lang['REGEX_ALPHA_NUM'] = '^[a-z0-9]+$';
$lang['REGEX_KEY_SERVER'] = 'trim|required';
$lang['REGEX_SERVICE_ID_SERVER'] = 'trim|alpha_numeric|required';
$lang['REGEX_CHANNEL_SERVER'] = 'trim|regex_match[/(BEM|BEP)/]|required';
$lang['REGEX_OPC_SERVER'] = 'trim|regex_match[/(BNC|BNT)/]';
$lang['REGEX_DOCUMENT_ID'] = $lang['REGEX_ALPHA_NUM'];
$lang['REGEX_DOCUMENT_ID_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_DOCUMENT_ID'] . '/i]';
$lang['REGEX_FISCAL_REGISTRY'] = '^["a-z0-9"]{8,9}';
