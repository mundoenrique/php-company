<?php defined('BASEPATH') or exit('No direct script access allowed');

$config = [
	'listenterprises' => [
		[
			'field' => 'plot',
			'rules' => 'trim|required'
		],
		[
			'field' => 'request',
			'rules' => 'trim|required'
		]
	],
	'validateCaptcha' => [
		[
			'field' => 'user',
			'label' => 'user',
			'rules' => 'trim|regex_match[/^([\wñÑ.\-+&]+)+$/i]|required'
		],
		[
			'field' => 'token',
			'label' => 'token',
			'rules' => 'trim|required'
		]
	],
	'login' => [
		[
			'field' => 'user',
			'label' => 'user',
			'rules' => 'trim|regex_match[/^([\wñÑ.\-+&]+)+$/i]|required'
		],
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|regex_match[/^([\w!@\*\-\?¡¿+\/.,#]+)+$/i]|required'
		]
	],
	'finishsession' => [
		[
			'field' => 'user',
			'label' => 'user',
			'rules' => 'trim|regex_match[/^([\wñÑ]+)+$/i]|required'
		]
	],
	'recoveryPass' => [
		[
			'field' => 'userName',
			'label' => 'userName',
			'rules' => 'trim|regex_match[/^([\wñÑ]+)+$/i]|required'
		],
		[
			'field' => 'idEmpresa',
			'label' => 'idEmpresa',
			'rules' => 'trim|regex_match[/^([\w-]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'email',
			'label' => 'email',
			'rules' => 'trim|regex_match[/^([a-zA-Z]+[0-9_.+-]*)+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/]|required'
		]
	],
	'changepassword' => [
		[
			'field' => 'currentPass',
			'label' => 'currentPass',
			'rules' => 'trim|regex_match[/^([\w!@\*\-\?¡¿+\/.,#]+)+$/i]|required'
		],
		[
			'field' => 'newPass',
			'label' => 'newPass',
			'rules' => 'trim|regex_match[/^([\w!@\*\-\?¡¿+\/.,#]+)+$/i]|differs[currentPass]|required'
		],
		[
			'field' => 'confirmPass',
			'label' => 'confirmPass',
			'rules' => 'trim|regex_match[/^([\w!@\*\-\?¡¿+\/.,#]+)+$/i]|matches[newPass]|required'
		]
	],
	'dash-products' => [
		[
			'field' => 'data-accodgrupoe',
			'label' => 'data-accodgrupoe',
			'rules' => 'trim|regex_match[/^([\w-]+)+$/i]|required'
		],
		[
			'field' => 'data-acrif',
			'label' => 'data-acrif',
			'rules' => 'trim|regex_match[/^([\w-]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'data-acnomcia',
			'label' => 'data-acnomcia',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'data-acrazonsocial',
			'label' => 'data-acrazonsocial',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'data-acdesc',
			'label' => 'data-acdesc',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]'
		],
		[
			'field' => 'data-accodcia',
			'label' => 'data-accodcia',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		]
	],
	'products-detail' => [
		[
			'field' => 'data-marcaProducto',
			'label' => 'data-marcaProducto',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'data-nombreProducto',
			'label' => 'data-nombreProducto',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'data-idproducto',
			'label' => 'data-idproducto',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		]
	],
	'enterprise' => [
		[
			'field' => 'group',
			'label' => 'group',
			'rules' => 'trim|regex_match[/^([\w-]+)+$/i]|required'
		],
		[
			'field' => 'fiscal-inf',
			'label' => 'fiscal-inf',
			'rules' => 'trim|regex_match[/^([\w-]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'name',
			'label' => 'name',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'business-name',
			'label' => 'business-name',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'description',
			'label' => 'description',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]'
		],
		[
			'field' => 'code',
			'label' => 'code',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		]
	],
	'products' => [
		[
			'field' => 'idProductoPost',
			'label' => 'idProductoPost',
			'rules' => 'trim|regex_match[/^([\w-]+)+$/i]|required'
		],
		[
			'field' => 'nomProduc',
			'label' => 'nomProduc',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'marcProduc',
			'label' => 'marcProduc',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		]
	]
];
