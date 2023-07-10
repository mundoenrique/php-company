<?php defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Pe';
$config['customer_uri'] = 'pe';
$config['base_url']	= BASE_URL.$config['customer_uri'].'/';
$config['language']	= BASE_LANGUAGE.'-'.$config['customer_uri'];
//borrar despues de la certificación
$config['language']	= BASE_LANGUAGE.'-peru';
