<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['session_time'] = SESS_EXPIRATION * 1000;
$config['client'] = 'novo';
$config['channel'] = 'ceo';
$config['format_date'] = 'j/m/Y';
$config['format_time'] = 'g:i A';
// APIs access
$config['urlAPI'] = API_URL;
$config['urlAPIContent'] = API_CONTENT_URL;
// Next-gen service access
$config['urlServ'] = SERVICE_URL;
$config['client_id'] = SERVICE_CLIENT_ID;
$config['client_secret'] = SERVICE_CLIENT_SECRET;
//
$config['client_style'] = 'default';
