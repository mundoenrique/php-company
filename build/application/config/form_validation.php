<?php defined('BASEPATH') or exit('No direct script access allowed');

$config = [
	'validateCaptcha' => [
		[
			'field' => 'user',
			'label' => 'user',
			'rules' => 'trim|regex_match[/^([\wñÑ.\-+&]+)+$/i]|required'
		],
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
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
			'rules' => 'trim|required'
		]
	],
	'singleSignon' => [
		[
			'field' => 'tokenId',
			'label' => 'tokenId',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		]
	],
	'finishSession' => [
		[
			'field' => 'user',
			'label' => 'user',
			'rules' => 'trim|regex_match[/^([\wñÑ]+)+$/i]|required'
		]
	],
	'recoverPass' => [
		[
			'field' => 'user',
			'label' => 'user',
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
	'changePassword' => [
		[
			'field' => 'currentPass',
			'label' => 'currentPass',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9=]+)+$/i]|required'
		],
		[
			'field' => 'newPass',
			'label' => 'newPass',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9=]+)+$/i]|required'
		],
		[
			'field' => 'confirmPass',
			'label' => 'confirmPass',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9=]+)+$/i]|required'
		]
	],
	'changeEmail' => [
		[
			'field' => 'email',
			'label' => 'email',
			'rules' => 'trim|required'
		]
	],
	'obtainNumPosition' => [
		[
			'field' => 'numpos',
			'rules' => 'trim'
		],

	],
	'changeTelephones' => [
		[
			'field' => 'tlf1',
			'label' => 'phone',
			'rules' => 'trim'
		],
		[
			'field' => 'tlf2',
			'label' => 'phone',
			'rules' => 'trim'
		],
		[
			'field' => 'tlf3',
			'label' => 'phone',
			'rules' => 'trim'
		],
	],
	'addContact' => [
		[
			'field' => 'contName',
			'label' => 'contName',
			'rules' => 'trim'
		],
		[
			'field' => 'password',
			'label' => 'password',
			'rules' => 'trim'
		],
		[
			'field' => 'surname',
			'label' => 'surname',
			'rules' => 'trim'
		],
		[
			'field' => 'contOcupation',
			'label' => 'contOcupation',
			'rules' => 'trim'
		],
		[
			'field' => 'contNIT',
			'label' => 'contNIT',
			'rules' => 'trim'
		],
		[
			'field' => 'contType',
			'label' => 'contType',
			'rules' => 'trim'
		],
		[
			'field' => 'contEmail',
			'label' => 'contEmail',
			'rules' => 'trim'
		]

	],
	'getProducts' => [
		[
			'field' => 'enterpriseCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]'
		],
		[
			'field' => 'enterpriseGroup',
			'label' => 'enterpriseGroup',
			'rules' => 'trim|regex_match[/^([\w-]+)+$/i]'
		],
		[
			'field' => 'idFiscal',
			'label' => 'idFiscal',
			'rules' => 'trim|regex_match[/^([\w-:.]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'enterpriseName',
			'label' => 'enterpriseName',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		]

	],
	'getProductDetail' => [
		[
			'field' => 'productPrefix',
			'label' => 'productPrefix',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required',
		],
		[
			'field' => 'productName',
			'label' => 'productName',
			'rules' => 'trim|regex_match[/^([\wñÑáéíóúÑÁÉÍÓÚ() ]+)+$/i]',
		],
		[
			'field' => 'productBrand',
			'label' => 'productBrand',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]',
		],
		[
			'field' => 'goToDetail',
			'label' => 'goToDetail',
			'rules' => 'trim|regex_match[/active/]',
		],
		[
			'field' => 'enterpriseCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]'
		],
		[
			'field' => 'enterpriseGroup',
			'label' => 'enterpriseGroup',
			'rules' => 'trim|regex_match[/^([\w-]+)+$/i]'
		],
		[
			'field' => 'idFiscal',
			'label' => 'idFiscal',
			'rules' => 'trim|regex_match[/^([\w-:.]+[\s]*)+$/i]'
		],
		[
			'field' => 'enterpriseName',
			'label' => 'enterpriseName',
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]'
		]
	],
	'loadBulk'  => [
		[
			'field' => 'branchOffice',
			'label' => 'branchOffice',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]'
		],
		[
			'field' => 'typeBulk',
			'label' => 'typeBulk',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]'
		],
		[
			'field' => 'formatBulk',
			'label' => 'formatBulk',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]'
		],
		[
			'field' => 'typeBulkText',
			'label' => 'typeBulkText',
			'rules' => 'trim|regex_match[/^[a-z0-9ñáéíóú ().]{10,70}$/i]'
		]
	],
	'getDetailBulk' => [
		[
			'field' => 'bulkTicked',
			'label' => 'bulkTicked',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		]
	],
	'confirmBulk' => [
		[
			'field' => 'bulkTicked',
			'label' => 'bulkTicked',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		]
	],
	'deleteNoConfirmBulk' => [
		[
			'field' => 'bulkId',
			'label' => 'bulkId',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]'
		],
		[
			'field' => 'bulkTicked',
			'label' => 'bulkTicked',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		],
		[
			'field' => 'bulkStatus',
			'label' => 'bulkStatus',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		],
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
		]
	],
	'signBulkList' => [
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
		],
		[
			'field' => 'bulk[]',
			'label' => 'bulk',
			'rules' => 'regex_match[/^([\w{}":,]*)+$/i]|required'
		]
	],
	'authorizeBulk' => [
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
		],
		[
			'field' => 'bulk[]',
			'label' => 'bulk',
			'rules' => 'regex_match[/^([\w{}":,]*)+$/i]|required'
		]
	],
	'deleteConfirmBulk' => [
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
		],
		[
			'field' => 'bulk[]',
			'label' => 'bulk',
			'rules' => 'regex_match[/^([\w{}":,]*)+$/i]|required'
		]
	],
	'disassConfirmBulk' => [
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
		],
		[
			'field' => 'bulk[]',
			'label' => 'bulk',
			'rules' => 'regex_match[/^([\w{}":,]*)+$/i]|required'
		]
	],
	'serviceOrder' => [
		[
			'field' => 'tempOrders',
			'label' => 'tempOrders',
			'rules' => 'trim|regex_match[/^([\w,]*)+$/i]'
		],
		[
			'field' => 'bulkNoBill',
			'label' => 'bulkNoBill',
			'rules' => 'trim|regex_match[/^([\w,]*)+$/i]'
		]
	],
	'bulkDetail' => [
		[
			'field' => 'bulkId',
			'label' => 'bulkId',
			'rules' => 'trim|regex_match[/^([\w]*)+$/i]|required'
		],
		[
			'field' => 'bulkfunction',
			'label' => 'bulkfunction',
			'rules' => 'trim|regex_match[/^([\wñáéíóú ]*)+$/i]|required'
		]
	],
	'cancelServiceOrder' => [
		[
			'field' => 'tempOrders',
			'label' => 'tempOrders',
			'rules' => 'trim|regex_match[/^([\w,]*)+$/i]'
		],
		[
			'field' => 'bulkNoBill',
			'label' => 'bulkNoBill',
			'rules' => 'trim|regex_match[/^([\w,]*)+$/i]'
		]
	],
	'clearServiceOrders' => [
		[
			'field' => 'idOS',
			'label' => 'idOS',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]'
		],
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
		]
	],
	'exportFiles' => [
		[
			'field' => 'OrderNumber',
			'label' => 'OrderNumber',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		],
		[
			'field' => 'who',
			'label' => 'who',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		],
		[
			'field' => 'where',
			'label' => 'where',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]|required'
		]
		],
	'getServiceOrders' => [
		[
			'field' => 'initialDate',
			'label' => 'initialDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'finalDate',
			'label' => 'finalDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'status',
			'label' => 'status',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]'
		],
		[
			'field' => 'statusText',
			'label' => 'statusText',
			'rules' => 'trim|regex_match[/^[\w -]+$/i]'
		]
	],
	'getReport' => [
		[
			'field' => 'operation',
			'label' => 'operation',
			'rules' => 'trim|regex_match[/^[\w]+$/i]'
		]
	],
	'deleteFile' => [
		[
			'field' => 'fileName',
			'label' => 'fileName',
			'rules' => 'trim|regex_match[/^[\w.]+$/i]'
		]
	],
	'unnamedRequest' => [
		[
			'field' => 'expiredDate',
			'label' => 'expiredDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'maxCards',
			'label' => 'maxCards',
			'rules' => 'trim|integer|required'
		],
		[
			'field' => 'startingLine1',
			'label' => 'startingLine1',
			'rules' => 'trim|alpha_numeric'
		],
		[
			'field' => 'startingLine2',
			'label' => 'startingLine2',
			'rules' => 'trim|alpha_numeric'
		],
		[
			'field' => 'branchOffice',
			'label' => 'branchOffice',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]'
		]
	],
	'keepSession' => [
		[
			'field' => 'modalReq',
			'label' => 'modalReq',
			'rules' => 'trim|required'
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
