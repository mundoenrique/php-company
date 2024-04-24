<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Ve';
$config['customer_files'] = CUSTOMER_URI;
$config['language'] = BASE_LANGUAGE . '-' . (DENY_WAY ? 'vene' : CUSTOMER_URI);
