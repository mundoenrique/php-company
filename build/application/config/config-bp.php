<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['country'] = 'Ec-bp';
$config['country-uri'] = 'bp';
$config['base_url']	= BASE_URL.$config['country-uri'].'/';
$config['language']	= 'spanish-bp';
$config['favicon'] = 'favicon-bp';
$config['new-views'] = '';
$config['client'] = 'pichincha';
$config['score_recaptcha'] = [
	'development' => 0,
	'testing' => 0,
	'production' => 0
];
$config['modalOtp'] = true;
$config['restartLogin'] = true;
