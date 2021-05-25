<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['client'] = 'pichincha';
$config['customer'] = 'Ec-bp';
$config['customer-uri'] = 'bp';
$config['base_url']	= BASE_URL.$config['customer-uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer-uri'];
//borrar despues de la certificación
$config['language']	= BASE_LANGUAGE.'-pich';

