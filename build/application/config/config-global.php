<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['session_time'] = SESS_EXPIRATION * 1000;
$config['session_call_server'] = $config['session_time'] < 30000 ? ceil($config['session_time'] * 50 / 100) : 15000;
$config['client'] = 'novo';
$config['channel'] = 'ceo';
$config['format_date'] = 'j/m/Y';
$config['format_time'] = 'g:i A';
$config['urlAPI'] = API_URL;
$config['urlAPIContent'] = API_CONTENT_URL;
$config['urlServ'] = SERVICE_URL;
$config['client_id'] = SERVICE_CLIENT_ID;
$config['client_secret'] = SERVICE_CLIENT_SECRET;
$config['customer_style'] = 'default';
$config['customer_files'] = 'default';
$config['client_db'] = [
	'bg' => 'bg',
	'bdb' => 'bdb',
	'bnt' => 'bnt',
	'bp' => 'bp',
	'col' => 'co',
	'coop' => 'coop',
	'pb' => 'pb',
	'per' => 'pe',
	'usd' => 'us',
	'ven' => 've',
	'vg' => 'vg'
];
