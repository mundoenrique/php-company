<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Ec-bp';
$config['customer_style'] = CUSTOMER_URI;
$config['customer_files'] = CUSTOMER_URI;
$config['language'] = BASE_LANGUAGE . '-' . (DENY_WAY ? 'pich' : CUSTOMER_URI);
