<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Bdb';
$config['customer_uri'] = 'bdb';
$config['customer_style'] = $config['customer_uri'];
$config['customer_lang'] = $config['customer_uri'];
$config['customer_program'] = $config['customer_uri'];
$config['base_url']	= BASE_URL.$config['customer_uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer_lang'];
