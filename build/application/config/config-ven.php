<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Ve';
$config['customer_uri'] = 'ven';
$config['customer_lang'] = 've';
$config['customer_files'] = $config['customer_lang'];
$config['base_url'] = BASE_URL . $config['customer_uri'] . '/';
$config['language'] = BASE_LANGUAGE . '-' . $config['customer_lang'];
