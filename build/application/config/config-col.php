<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Co';
$config['customer_uri'] = 'col';
$config['customer_lang'] = 'co';
$config['customer_program'] = $config['customer_lang'];
$config['base_url']	= BASE_URL.$config['customer_uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer_lang'];
//$config['language']	= BASE_LANGUAGE.'-co';
