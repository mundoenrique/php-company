<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Usd';
$config['customer-uri'] = 'us';
$config['base_url']	= BASE_URL.$config['customer-uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer-uri'];
//borrar despues de la certificación
$config['language']	= BASE_LANGUAGE.'-dol';
