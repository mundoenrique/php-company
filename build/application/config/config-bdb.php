<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['country'] = 'Bdb';
$config['country-uri'] = 'bdb';
$config['base_url']	= BASE_URL.$config['country-uri'].'/';
$config['language']	= 'spanish-bdb';
$config['favicon'] = 'favicon-bdb';
$config['client'] = 'banco-bog';
$config['access_url'] = [$config['country-uri']];
$config['new-views'] = '-core';
$config['url_bulk_service'] = BULK_FTP_URL.$config['country'].'/';
