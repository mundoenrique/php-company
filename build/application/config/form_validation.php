<?php defined('BASEPATH') or exit('No direct script access allowed');

$config = [
	'signIn' => [
		[
			'field' => 'userName',
			'rules' => 'trim|regex_match[/^([\wñÑ.\-+&]+)+$/i]|required'
		],
		[
			'field' => 'userPass',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9=]+)+$/i]|required'
		],
		[
			'field' => 'otpCode',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]'
		],
		[
			'field' => 'saveIP',
			'rules' => 'trim'
		]
	],
	'singleSignOn' => [
		[
			'field' => 'sessionId',
			'rules' => 'trim'
		],
		[
			'field' => 'clave',
			'rules' => 'trim'
		],
		[
			'field' => 'IdServicio',
			'rules' => 'trim'
		],
		[
			'field' => 'canal',
			'rules' => 'trim'
		]
	],
	'finishSession' => [
		[
			'field' => 'userName',
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
			'rules' => 'trim|regex_match[/^([\w\-]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'email',
			'label' => 'email',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9]*)+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/]|required'
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
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9]*)+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/]|required'
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
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9]*)+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/]|required'
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
	'exportToExcelExtendedMasterAccount' => [
		[
			'field' => 'idExtEmpXls',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
		[
			'field' => 'filterDateXls',
			'rules' => 'trim|regex_match[/^[0|3|6]*$/i]|required'
		],
		[
			'field' => 'initialDate',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'finalDate',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'nameEnterpriseXls',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9., ]*$/i]|required'
		]
	],
	'extendedDownloadMasterAccountCon' => [
		[
			'field' => 'idExtEmpXls',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
		[
			'field' => 'filterDateXls',
			'rules' => 'trim|regex_match[/^[0|3|6]*$/i]|required'
		],
		[
			'field' => 'initialDate',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'finalDate',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'nameEnterpriseXls',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9., ]*$/i]|required'
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
	'extendedMasterAccount' => [
		[
			'field' => 'idExtEmp',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
		[
			'field' => 'typeNote',
			'rules' => 'trim|regex_match[/^[D|C]*$/i]'
		],
		[
			'field' => 'initialDate',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'finalDate',
			'rules' => 'trim|regex_match[/^[0-9\/]*$/i]'
		],
		[
			'field' => 'filterDate',
			'rules' => 'trim|regex_match[/^[0|3|6]*$/i]|required'
		]
	],
	'statusMasterAccount' => [
		[
			'field' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^([\w]+)+$/i]'
		],
		[
			'field' => 'initialDateAct',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		]
	],
	'changeTelephones' => [
		[
			'field' => 'phone1',
			'label' => 'phone1',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
		[
			'field' => 'phone2',
			'label' => 'phone2',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'phone3',
			'label' => 'phone3',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		]
	],
	'changeDataEnterprice' => [
		[
			'field' => 'address',
			'label' => 'address',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ# (),.-\/]+$/]|required'
		],
		[
			'field' => 'billingAddress',
			'label' => 'billingAddress',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ# (),.-\/]+$/]'
		]
	],
	'deleteContact' => [
		[
			'field' => 'acrif',
			'label' => 'acrif',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
		[
			'field' => 'idExper',
			'label' => 'idExper',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim|required'
		]
	],
	'updateContact' => [
		[
			'field' => 'nameModifyContact',
			'label' => 'nameModifyContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z ]*$/i]|required'
		],
		[
			'field' => 'dniModifyContact',
			'label' => 'dniModifyContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9 ]*$/i]|required'
		],
		[
			'field' => 'emailModifyContact',
			'label' => 'emailModifyContact',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9]*)+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/]|required'
		],
		[
			'field' => 'modifyContactPass',
			'label' => 'modifyContactPass',
			'rules' => 'trim|required'
		],
		[
			'field' => 'positionModifyContact',
			'label' => 'positionModifyContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z ]*$/i]|required'
		],
		[
			'field' => 'surnameModifyContact',
			'label' => 'surnameModifyContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z ]*$/i]|required'
		],
		[
			'field' => 'typeModifyContact',
			'label' => 'typeModifyContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z ]*$/i]|required'
		]
	],
	'addContact' => [
		[
			'field' => 'nameNewContact',
			'label' => 'nameNewContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z ]*$/i]|required'
		],
		[
			'field' => 'dniNewContact',
			'label' => 'dniNewContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9 ]*$/i]|required'
		],
		[
			'field' => 'emailNewContact',
			'label' => 'emailNewContact',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9]*)+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/]|required'
		],
		[
			'field' => 'newContPass',
			'label' => 'newContPass',
			'rules' => 'trim|required'
		],
		[
			'field' => 'positionNewContact',
			'label' => 'positionNewContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z ]*$/i]|required'
		],
		[
			'field' => 'surnameNewContact',
			'label' => 'surnameNewContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z ]*$/i]|required'
		],
		[
			'field' => 'typeNewContact',
			'label' => 'typeNewContact',
			'rules' => 'trim|regex_match[/^[a-zA-Z ]*$/i]|required'
		]
	],
	'getProducts' => [
		[
			'field' => 'enterpriseCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^([\w\- ]+)+$/i]'
		],
		[
			'field' => 'enterpriseGroup',
			'label' => 'enterpriseGroup',
			'rules' => 'trim|regex_match[/^([\w\-]+)+$/i]'
		],
		[
			'field' => 'idFiscal',
			'label' => 'idFiscal',
			'rules' => 'trim|regex_match[/^([\w\-:.]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'enterpriseName',
			'label' => 'enterpriseName',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]'
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
			'rules' => 'trim|regex_match[/^([\wñÑáéíóúÑÁÉÍÓÚ\(\) ]+)+$/i]',
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
			'rules' => 'trim|regex_match[/^([\w\-:.]+[\s]*)+$/i]'
		],
		[
			'field' => 'enterpriseName',
			'label' => 'enterpriseName',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]'
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
			'rules' => 'trim|regex_match[/^[a-z0-9ñáéíóú \(\).]{10,70}$/i]'
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
		],
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim'
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
			'rules' => 'trim'
		]
	],
	'signBulkList' => [
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim'
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
			'rules' => 'trim'
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
			'rules' => 'trim'
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
			'rules' => 'trim|regex_match[/^([\w\-ñáéíóú ]+[\s]*)+$/i]'
		]
	],
	'actionMasterAccount' => [
		[
			'field' => 'pass',
			'label' => 'pass',
			'rules' => 'trim'
		],
		[
			'field' => 'reference',
			'label' => 'reference',
			'rules' => 'regex_match[/^([a-zA-Z0-9\ñ\Ñ]{1}[a-zA-Z0-9-z\.\-\_\ \#\%\/\Ñ\ñ]{0,39})+$/i]'
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
	'masterAccountTransfer' => [
		[
			'field' => 'transferAmount',
			'label' => 'transferAmount',
			'rules' => 'trim'
		],
		[
			'field' => 'description',
			'label' => 'description',
			'rules' => 'trim'
		],
		[
			'field' => 'passwordTranfer',
			'label' => 'passwordTranfer',
			'rules' => 'trim'
		]
	],
	'rechargeAuthorization' => [
		[
			'field' => 'passwordTranfer',
			'rules' => 'trim'
		]
	],
	'pagoOs' => [
		[
			'field' => 'idOS',
			'rules' => 'trim'
		]
	],
	'pagarOS' => [
		[
			'field' => 'idOS',
			'rules' => 'trim'
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
			'rules' => 'trim|regex_match[/^[\w.\-]+$/i]'
		]
	],
	'getFileIni' => [
		[
			'field' => 'operation',
			'label' => 'operation',
			'rules' => 'null'
		]
	],
	'getBranches' => [
		[
			'field' => 'branchListBr',
			'label' => 'branchListBr',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		]
	],
	'getContacts' =>[
		[
			'field' => 'acrif',
			'label' => 'acrif',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
	],
	'uploadFileBranches' => [
		[
			'field' => 'file',
			'label' => 'file',
			'rules' => 'trim|regex_match[/^[0-9a-zA-z.\/]+$/]|required'
		],
		[
			'field' => 'typeBulkText',
			'label' => 'typeBulkText',
			'rules' => 'trim|regex_match[/^[a-z0-9ñáéíóú \(\).]{10,70}$/i]'
		]
	],
	'geoFilter' => [
		[
			'field' => 'data[]',
			'label' => 'data',
			'rules' => 'required'
		]
	],
	'updateBranch' => [
		[
			'field' => 'rifB',
			'label' => 'rifB',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]'
		],
		[
			'field' => 'codB',
			'label' => 'codB',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]'
		],
		[
			'field' => 'branchName',
			'label' => 'branchName',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ (),.-\/]+$/]|required'
		],
		[
			'field' => 'address1',
			'label' => 'address1',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ (),.-\/]+$/]|required'
		],
		[
			'field' => 'address2',
			'label' => 'address2',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ (),.-\/]+$/]'
		],
		[
			'field' => 'address3',
			'label' => 'address3',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ (),.-\/]+$/]'
		],
		[
			'field' => 'countryCodeBranch',
			'label' => 'countryCodeBranch',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'stateCodeBranch',
			'label' => 'stateCodeBranch',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'cityCodeBranch',
			'label' => 'cityCodeBranch',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'person',
			'label' => 'person',
			'rules' => 'trim|regex_match[/^[a-zA-ZñÑáéíóúÁÉÍÓÚ \/]+$/]|required'
		],
		[
			'field' => 'areaCode',
			'label' => 'areaCode',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'phone',
			'label' => 'phone',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'userNameB',
			'label' => 'userNameB',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\/]+$/]'
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
			'rules' => 'regex_match[/^([\w{}"*:.\-+,@ñÑáéíóúÑÁÉÍÓÚ ]*)+$/i]|required'
		],
		[
			'field' => 'action',
			'label' => 'action',
			'rules' => 'trim|regex_match[/^([\w ]*)+$/i]|required'
		],
		[
			'field' => 'password',
			'label' => 'password',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9=]+)+$/i]'
		]
	],
	'authorizationKey' => [
		[
			'field' => 'action',
			'label' => 'action',
			'rules' => 'trim|regex_match[/^([\wñáéíóú ]+)+$/i]|required'
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
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
		],
		[
			'field' => 'productCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
		],
	],
	'exportReportCardHolders' => [
		[
			'field' => 'enterpriseName',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
		],
		[
			'field' => 'productName',
			'rules' => 'trim|regex_match[/^([\wñÑáéíóúÑÁÉÍÓÚ\(\) ]+)+$/i]|required'
		],
		[
			'field' => 'productCode',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
		],
		[
			'field' => 'downloadFormat',
			'rules' => 'trim|regex_match[/^[a-zA-Z]+$/i]|required'
		]
	],
	'rechargeMade' => [
		[
			'field' => 'enterpriseCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]|required'
		],
		[
			'field' => 'initialDatemy',
			'label' => 'initialDatemy',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		]
	],
	'issuedCards' => [
		[
			'field' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]|required'
		],
		[
			'field' => 'monthYear',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]'
		],
		[
			'field' => 'initDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]'
		],
		[
			'field' => 'finalDate',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]'
		],
		[
			'field' => 'queryType',
			'rules' => 'trim|regex_match[/^(0|1)/]|required'
		],
		[
			'field' => 'type',
			'rules' => 'trim|regex_match[/^(info|download)/]|required'
		],
		[
			'field' => 'fiscalId',
			'rules' => 'trim|regex_match[/^([\w\-]+[\s]*)+$/i]'
		],
		[
			'field' => 'enterpriseName',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]'
		],
		[
			'field' => 'format',
			'rules' => 'trim|regex_match[/^(xls|pdf)/]'
		]
	],
	'userActivity' => [
		[
			'field' => 'enterpriseCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
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
	'exportReportUserActivity' => [
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
			'field' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
		],
		[
			'field' => 'rifEnterprise',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		],
		[
			'field' => 'downloadFormat',
			'rules' => 'trim|regex_match[/^[a-zA-Z]+$/i]|required'
		]
	],
	'usersActivity' => [
		[
			'field' => 'enterpriseCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
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
	'exportExcelUsersActivity' => [
		[
			'field' => 'enterpriseCode',
			'label' => 'enterpriseCode',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
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
	'commercialTwirls' => [
		[
			'field' => 'cardNumber',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		]
	],
	'updateCommercialTwirls' => [
		[
			'field' => 'passwordAuth',
			'rules' => 'trim'
		],
		[
			'field' => 'companyId',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]'
		],
		[
			'field' => 'product',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'travelAgency',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'insurers',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'charity',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'collegesUniversities',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'entertainment',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'parking',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'gaStations',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'governments',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'hospitals',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'hotels',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'debit',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'toll',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'restaurants',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'supermarkets',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'telecommunication',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'airTransport',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'passengerTransportation',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		],
		[
			'field' => 'retailSales',
			'rules' => 'trim|regex_match[/^[0-1]*$/i]|required'
		]
	],
	'transactionalLimits' => [
		[
			'field' => 'cardNumber',
			'rules' => 'trim|regex_match[/^[0-9]*$/i]|required'
		]
	],
	'updateTransactionalLimits' => [
		[
			'field' => 'passwordAuth',
			'rules' => 'trim'
		],
		[
			'field' => 'dailyNumberCredit',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'dailyAmountCredit',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'monthlyNumberCredit',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'monthlyAmountCredit',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'CreditTransaction',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'weeklyNumberCredit',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'weeklyAmountCredit',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'numberDayPurchasesCtp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'dailyPurchaseamountCtp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'numberMonthlyPurchasesCtp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'monthlyPurchasesAmountCtp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'purchaseTransactionCtp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'numberDayPurchasesStp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'dailyPurchaseamountStp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'numberMonthlyPurchasesStp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'monthlyPurchasesAmountStp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'purchaseTransactionStp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'numberWeeklyPurchasesStp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'weeklyAmountPurchasesStp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'numberWeeklyPurchasesCtp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'weeklyAmountPurchasesCtp',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'dailyNumberWithdraw',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'dailyAmountWithdraw',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'monthlyNumberWithdraw',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'monthlyAmountwithdraw',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'WithdrawTransaction',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'weeklyNumberWithdraw',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		],
		[
			'field' => 'weeklyAmountWithdraw',
			'rules' => 'trim|regex_match[/^[0-9,.]*$/i]'
		]
	],
	'searchStatusAccount' => [
		[
			'field' => 'resultByNITInput',
			'rules' => 'trim|regex_match[/^[a-z0-9]*$/i]'
		],
		[
			'field' => 'resultByCardInput',
			'rules' => 'trim|numeric',
		],
		[
			'field' => 'initialDateAct',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		]
	],
	'searchExtendedAccountStatus' => [
		[
			'field' => 'resultByNITInput',
			'rules' => 'trim|regex_match[/^[a-z0-9]*$/i]'
		],
		[
			'field' => 'resultByCardInput',
			'rules' => 'trim|numeric',
		],
		[
			'field' => 'initialDateAct',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		]
	],
	'statusAccountExcelFile' => [
		[
			'field' => 'resultByNITInput',
			'rules' => 'trim|regex_match[/^[a-z0-9]*$/i]'
		],
		[
			'field' => 'initialDateAct',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		]
	],
	'exportToExcelExtendedAccountStatus' => [
		[
			'field' => 'resultByNITInputXls',
			'rules' => 'trim|regex_match[/^[a-z0-9]*$/i]'
		],
		[
			'field' => 'enterpriseCodeXls',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
		],
		[
			'field' => 'productCodeXls',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9\-.]+$/i]|required'
		],
		[
			'field' => 'initialDateActXls',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		],
		[
			'field' => 'resultSearchXls',
			'rules' => 'trim|numeric|required'
		]
	],
	'statusAccountPdfFile' => [
		[
			'field' => 'resultByNITInput',
			'rules' => 'trim|regex_match[/^[a-z0-9]*$/i]'
		],
		[
			'field' => 'initialDateAct',
			'rules' => 'trim|regex_match[/^[0-9\/]+$/]|required'
		]
	],
	'userPermissions' => [
			[
				'field' => 'adminUser',
				'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]',
			],
	],
	'usersManagement' => [
		[
			'field' => 'adminUser',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'adminName',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'adminMail',
			'rules' => 'trim|regex_match[/^([a-zA-Z]+[0-9_.+\-]*)+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/]'
		],
		[
			'field' => 'adminType',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
	],
	'updatePermissions' => [
		[
			'field' => 'idUser',
			'rules' => 'trim|regex_match[/^[A-Za-z0-9]*$/i]|required'
		],
		[
			'field' => 'ACTGIR',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'ACTLIM',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'ASGPER',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'CONGIR',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'CONLIM',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'CONUSU',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'CREUSU',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'OPCONL',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'REPCON',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'REPEDO',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'REPLOT',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'REPPRO',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'REPTAR',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'REPUSU',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'REPEDC',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'REPCMT',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TEBANU',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TEBPGO',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TEBCON',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TEBCOS',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TEBELC',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TEBELI',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TEBTHA',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TIREPO',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TRAABO',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TRAASG',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TRABLQ',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TRACAR',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TRADBL',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TRAPGO',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		],
		[
			'field' => 'TRASAL',
			'rules' => 'trim|regex_match[/^[a-z]*$/i]'
		]
	],
	'enableUser' => [
		[
			'field' => 'user',
			'rules' => 'trim|regex_match[/^[a-zA-Z0-9]*$/i]'
		],
		[
			'field' => 'name',
			'rules' => 'trim'
		],
		[
			'field' => 'mail',
			'rules' => 'trim|regex_match[/^([a-zA-Z]+[0-9_.+\-]*)+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/]'
		],
	],
	'keepSession' => [
		[
			'field' => 'modalReq',
			'label' => 'modalReq',
			'rules' => 'trim'
		]
	],
	'changeLanguage' => [
		[
			'field' => 'lang',
			'rules' => 'trim|alpha|regex_match[/es|en/]|required'
		]
	],
	// Old Arquitectures
	'login' => [
		[
			'field' => 'userName',
			'rules' => 'trim|regex_match[/^([\wñÑ.\-+&]+)+$/i]|required'
		],
		[
			'field' => 'userPass',
			'rules' => 'trim|regex_match[/^([a-zA-Z0-9=]+)+$/i]|required'
		],
		[
			'field' => 'otpCode',
			'rules' => 'trim|regex_match[/^[a-z0-9]+$/i]'
		],
		[
			'field' => 'saveIP',
			'rules' => 'trim'
		]
	],
	'dash-products' => [
		[
			'field' => 'data-accodgrupoe',
			'label' => 'data-accodgrupoe',
			'rules' => 'trim|regex_match[/^([\w\-]+)+$/i]|required'
		],
		[
			'field' => 'data-acrif',
			'label' => 'data-acrif',
			'rules' => 'trim|regex_match[/^([\w\-]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'data-acnomcia',
			'label' => 'data-acnomcia',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'data-acrazonsocial',
			'label' => 'data-acrazonsocial',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'data-acdesc',
			'label' => 'data-acdesc',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]'
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
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'data-nombreProducto',
			'label' => 'data-nombreProducto',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
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
			'rules' => 'trim|regex_match[/^([\w\-]+)+$/i]|required'
		],
		[
			'field' => 'fiscal-inf',
			'label' => 'fiscal-inf',
			'rules' => 'trim|regex_match[/^([\w\-]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'name',
			'label' => 'name',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'business-name',
			'label' => 'business-name',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'description',
			'label' => 'description',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]'
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
			'rules' => 'trim|regex_match[/^([\w\-]+)+$/i]|required'
		],
		[
			'field' => 'nomProduc',
			'label' => 'nomProduc',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		],
		[
			'field' => 'marcProduc',
			'label' => 'marcProduc',
			'rules' => 'trim|regex_match[/^([\w\-.,#ñÑáéíóúÑÁÉÍÓÚ\(\)&:\+]+[\s]*)+$/i]|required'
		]
	]
];
