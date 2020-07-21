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
		],
		[
			'field' => 'codeOTP',
			'label' => 'codeOTP',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]'
		],
		[
			'field' => 'saveIP',
			'label' => 'saveIP',
			'rules' => 'trim'
		]
	],
	'singleSignon' => [
		[
			'field' => 'sessionId',
			'label' => 'sessionId',
			'rules' => 'trim|required'
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
	'recoverAccess' => [
		[
			'field' => 'documentType',
			'label' => 'documentType',
			'rules' => 'trim|alpha|required'
		],
		[
			'field' => 'documentId',
			'label' => 'documentId',
			'rules' => 'trim|alpha_numeric|required'
		],
		[
			'field' => 'email',
			'label' => 'email',
			'rules' => 'trim|regex_match[/^([a-zA-Z]+[0-9_.+-]*)+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/]|required'
		]
	],
	'validateOtp' => [
		[
			'field' => 'optCode',
			'label' => 'optCode',
			'rules' => 'trim|alpha_numeric|required'
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
			'rules' => 'trim|regex_match[/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix]|required'
		]
	],
	'obtainNumPosition' => [
		[
			'field' => 'acrif',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
		[
			'field' => 'numpos',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'nameBusine',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9 .,]*$/i]|required'
		],
		[
			'field' => 'razonSocial',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9 .,]*$/i]|required'
		],
		[
			'field' => 'contacto',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9 ]*$/i]'
		],
		[
			'field' => 'fact',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9,.´ ]*$/i]'
		],
		[
			'field' => 'ubicacion',
			'rules' => 'trim|regex_match[/^[a-zA-Z 0-9.,´ ]*$/i]'
		],
		[
			'field' => 'tel1',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'tel2',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'tel3',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		]
	],

	'closingBudgets' => [
		[
			'field' => 'idExtPer',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'producto',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'paginaActual',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'tamanoPagina',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
	],
	'exportToExcel' => [
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'producto',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'tamanoPagina',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'paginaActual',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'descProd',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
	],
	'exportToExcel' => [
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'producto',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'tamanoPagina',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'paginaActual',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'descProd',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
	],
	'exportToExcelMasterAccount' => [
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'producto',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'tamanoPagina',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'paginaActual',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'descProd',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
	],
	'exportToExcelMasterAccount' => [
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'filtroFecha',
			'rules' => 'trim|regex_match[/^[0|3|6]*$/i]'
		],
		[
			'field' => 'fechaIni',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'fechaFin',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'tamanoPagina',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'paginaActual',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'producto',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'nombreEmpresa',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9., ]*$/i]'
		]
	],
	'exportToPDFMasterAccount' => [
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'filtroFecha',
			'rules' => 'trim|regex_match[/^[0|3|6]*$/i]'
		],
		[
			'field' => 'fechaIni',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'fechaFin',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'tamanoPagina',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'paginaActual',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'producto',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'nombreEmpresa',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9., ]*$/i]'
		]
	],
	'exportToExcelMasterAccountConsolid' => [
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'anio',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'fechaIni',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'fechaFin',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'nombreEmpresa',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9 ]*$/i]'
		],
		[
			'field' => 'paginaActual',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'producto',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'tamanoPagina',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		]

	],
	'exportToPDFMasterAccountConsolid' => [
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'anio',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'fechaIni',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'fechaFin',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'nombreEmpresa',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9 ]*$/i]'
		],
		[
			'field' => 'paginaActual',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'producto',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'tamanoPagina',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		]
	],
	'masterAccount' => [
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'tipoNota',
			'rules' => 'trim|regex_match[/^[D|C]*$/i]'
		],
		[
			'field' => 'fechaIni',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'fechaFin',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'filtroFecha',
			'rules' => 'trim|regex_match[/^[0|3|6]*$/i]'
		],
		[
			'field' => 'paginaActual',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'tamanoPagina',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		]
	],
	'changeTelephones' => [
		[
			'field' => 'tlf1',
			'label' => 'phone',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
		[
			'field' => 'tlf2',
			'label' => 'phone',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'tlf3',
			'label' => 'phone',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
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
			'rules' => 'trim|regex_match[/^([\w-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]'
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
		],
		[
			'field' => 'otpCode',
			'label' => 'otpCode',
			'rules' => 'trim|alpha_numeric'
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
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]'
		],
		[
			'field' => 'bulkNumber',
			'label' => 'bulkNumber',
			'rules' => 'trim|integer'
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
			'rules' => 'trim|regex_match[/^([\wñáéíóú ]*)+$/i]'
		],
		[
			'field' => 'statusText',
			'label' => 'statusText',
			'rules' => 'trim|regex_match[/^([\w-ñáéíóú ]+[\s]*)+$/i]'
		]
	],
	'actionMasterAccount' => [
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
		],
		[
			'field' => 'cards[]',
			'label' => 'cards',
			'rules' => 'regex_match[/^([\w{}"*:.,]*)+$/i]|required'
		],
		[
			'field' => 'action',
			'label' => 'action',
			'rules' => 'trim|regex_match[/^([\wñáéíóú ]+)+$/i]|required'
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
	'getFileIni' => [
		[
			'field' => 'operation',
			'label' => 'operation',
			'rules' => 'null'
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
			'rules' => 'trim|regex_match[/^[a-z0-9 ]+$/i]'
		],
		[
			'field' => 'startingLine2',
			'label' => 'startingLine2',
			'rules' => 'trim|regex_match[/^[a-z0-9 ]+$/i]'
		],
		[
			'field' => 'branchOffice',
			'label' => 'branchOffice',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]'
		]
	],
	'unnamedAffiliate' => [
		[
			'field' => 'bulkNumber',
			'label' => 'bulkNumber',
			'rules' => 'trim|integer'
		],
		[
			'field' => 'initialDate',
			'label' => 'initialDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]'
		],
		[
			'field' => 'finalDate',
			'label' => 'finalDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]'
		]
	],
	'unnmamedDetail' => [
		[
			'field' => 'bulkNumber',
			'label' => 'bulkNumber',
			'rules' => 'trim|integer|required'
		],
		[
			'field' => 'totalCards',
			'label' => 'totalCards',
			'rules' => 'trim|integer|required'
		],
		[
			'field' => 'issuanDate',
			'label' => 'issuanDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		]
	],
	'transfMasterAccount' => [
		[
			'field' => 'idNumber',
			'label' => 'idNumber',
			'rules' => 'trim|alpha_numeric'
		],
		[
			'field' => 'cardNumber',
			'label' => 'cardNumber',
			'rules' => 'trim|integer'
		],
		[
			'field' => 'draw',
			'label' => 'draw',
			'rules' => 'trim|integer|required'
		],
		[
			'field' => 'length',
			'label' => 'length',
			'rules' => 'trim|integer|required'
		]
	],
	'cardsInquiry' => [
		[
			'field' => 'orderNumber',
			'label' => 'orderNumber',
			'rules' => 'trim|numeric'
		],
		[
			'field' => 'bulkNumber',
			'label' => 'bulkNumber',
			'rules' => 'trim|numeric'
		],
		[
			'field' => 'idNumberP',
			'label' => 'idNumberP',
			'rules' => 'trim|alpha_numeric'
		],
		[
			'field' => 'cardNumberP',
			'label' => 'cardNumberP',
			'rules' => 'trim|integer'
		],
	],
	'inquiriesActions' => [
		[
			'field' => 'cards[]',
			'label' => 'cards',
			'rules' => 'regex_match[/^([\w{}"*:.,@ñÑáéíóúÑÁÉÍÓÚ ]*)+$/i]|required'
		],
		[
			'field' => 'action',
			'label' => 'action',
			'rules' => 'trim|regex_match[/^([\w ]*)+$/i]|required'
		],
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9=]+)+$/i]|required'
		]
	],
	'statusBulk' => [
		[
			'field' => 'enterpriseCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]|required'
		],
		[
			'field' => 'productCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]|required'
		],
		[
			'field' => 'initialDate',
			'label' => 'initialDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'finalDate',
			'label' => 'finalDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		]
	],
	'cardHolders' => [
		[
			'field' => 'enterpriseCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]|required'
		],
		[
			'field' => 'productCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]|required'
		],
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
		],
	'userActivity' => [
		[
			'field' => 'fechaIni',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'fechaFin',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'acCodCia',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		]
	],
	'exportToExcelUserActivity' => [
		[
			'field' => 'rifEmpresa',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'fechaIni',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'fechaFin',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'acCodCia',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		]
	],
	'exportToPDFUserActivity' => [
		[
			'field' => 'rifEmpresa',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'fechaIni',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'fechaFin',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'acCodCia',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		]
	],
	'commercialTwirls' => [
		[
			'field' => 'idNumberP',
			'rules' => 'trim|regex_match[/^[a-z0-9]*$/i]|required'
		],
		[
			'field' => 'cardNumberP',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
	],
	'updateCommercialTwirls' => [
		[
			'field' => 'password-auth',
			'rules' => 'trim|required'
		]
	]
];
