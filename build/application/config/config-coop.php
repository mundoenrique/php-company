<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'coop';
$config['customer-uri'] = $config['customer'];
$config['client_style'] = $config['customer'];
$config['base_url']	= BASE_URL.$config['customer-uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer-uri'];
