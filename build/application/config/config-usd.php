<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Usd';
$config['customer-uri'] = 'usd';
$config['base_url']	= BASE_URL.$config['customer-uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer-uri'];
$config['language']	= BASE_LANGUAGE.'-us';
