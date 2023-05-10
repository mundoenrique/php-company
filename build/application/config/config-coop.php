<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'coop';
$config['customer_uri'] = $config['customer'];
$config['customer_style'] = $config['customer'];
$config['customer_lang'] = $config['customer'];
$config['customer_program'] = $config['customer'];
$config['base_url']	= BASE_URL.$config['customer_uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer_lang'];
