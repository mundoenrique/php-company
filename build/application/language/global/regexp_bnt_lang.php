<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['REGEX_KEY_SERVER'] = 'trim|required';
$lang['REGEX_SERVICE_ID_SERVER'] = 'trim|number|required';
$lang['REGEX_CHANNEL_SERVER'] = 'trim|regex_match[/(BEM|BEP)/]|required';
$lang['REGEX_OPC_SERVER'] = 'trim|regex_match[/(BNC|BNT)/]';
