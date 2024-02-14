<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['REGEX_NUMERIC'] = '^[0-9]+$';
$lang['REGEX_ALPHA_NUM'] = '^[a-z0-9]+$';
$lang['REGEX_DOCUMENT_ID'] = $lang['REGEX_ALPHA_NUM'];
$lang['REGEX_DOCUMENT_ID_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_DOCUMENT_ID'] . '/i]';
$lang['REGEX_FISCAL_REGISTRY'] = '^([0-9]{9,17})';
