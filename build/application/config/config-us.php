<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Usd';
$config['customer_uri'] = 'us';
$config['customer_lang'] = 'us';
$config['customer_files'] = $config['customer_lang'];
$config['base_url']	= BASE_URL . $config['customer_uri'] . '/';
$config['language']	= BASE_LANGUAGE . '-' . $config['customer_lang'];
