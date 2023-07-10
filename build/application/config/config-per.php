<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Pe';
$config['customer_uri'] = 'per';
$config['customer_lang'] = 'pe';
$config['customer_program'] = $config['customer_lang'];
$config['base_url']	= BASE_URL.$config['customer_uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer_lang'];
