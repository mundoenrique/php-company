<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Ec-bp';
$config['customer-uri'] = 'bp';
$config['client_style'] = $config['customer-uri'];
$config['base_url']	= BASE_URL.$config['customer-uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer-uri'];
