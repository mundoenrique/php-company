<?php
defined('BASEPATH') or exit('No direct script access allowed');


$lang['REGEX_FISCAL_ID'] = setRegex('fiscal_id_ecu');
$lang['REGEX_FISCAL_ID_SERVER'] = 'trim|regex_match[/' . $lang['REGEX_FISCAL_ID'] . '/i]|required';
