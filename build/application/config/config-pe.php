<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['customer'] = 'Pe';
$config['customer_files'] = CUSTOMER_URI;
$config['language'] = BASE_LANGUAGE . '-' . (DENY_WAY ? 'peru' : CUSTOMER_URI);
