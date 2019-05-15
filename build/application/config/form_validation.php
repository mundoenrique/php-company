<?php defined('BASEPATH') or exit('No direct script access allowed');

$config = [
	'filter-xss' => [
		'field' => 'any',
		'label' => 'any',
		'rules' => 'trim|xss_clean'
	],
	'login' => [
		[
			'field' => 'user',
			'label' => 'user',
			'rules' => 'trim|xss_clean'
		],
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
		]
	]
];
