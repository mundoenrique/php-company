<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los lotes
 * @author J. Enrique Peñaloza Piñero
 * @date December 8th, 2019
 */
class Novo_Bulk_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Bulk Model Class Initialized');
	}
	/**
	 * @info Método para obtener los tipos de lte asociados a un programa
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 8th, 2019
	 */
	public function callWs_getPendingBulk_Bulk()
	{
		log_message('INFO', 'NOVO Bulk Model: getPendingBulk Method Initialized');

		$this->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar Lotes';
		$this->dataAccessLog->operation = 'Lotes por confirmar';

		$this->dataRequest->idOperation = 'buscarLotesPorConfirmar';
		$this->dataRequest->lotesTO = [
			'idEmpresa' => $this->session->enterpriseInf->idFiscal,
			'codProducto' => $this->session->productInf->productPrefix
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName
		];

		$response = $this->sendToService(lang('GEN_GET_PEN_BULK'));
		$pendingBulkList = [];

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				foreach($response->lista AS $pos => $pendingBulk) {
					$bulk = [];
					$bulk['lotNum'] = $response->lista[$pos]->numLote != '' ? $response->lista[$pos]->numLote : '---';
					$bulktatus = $response->lista[$pos]->estatus;

					switch ($bulktatus) {
						case '0':
							$bulk['statusPr'] = '';
							$bulk['statusColor'] = ' bg-gold-sand';
							$bulk['statusText'] = lang('BULK_VALIDATING');
							break;
						case '1':
							$bulk['statusPr'] = 'status-pr ';
							$bulk['statusColor'] = ' bg-vista-blue';
							$bulk['statusText'] = lang('BULK_VALID');
							break;
						case '5':
							$bulk['statusPr'] = '';
							$bulk['statusColor'] = ' bg-pink-salmon';
							$bulk['statusText'] = lang('BULK_NO_VALID');
							break;
						case '6':
							$bulk['statusPr'] = 'status-pr ';
							$bulk['statusColor'] = ' bg-trikemaster';
							$bulk['statusText'] = lang('BULK_VALID');
							break;
					}

					$bulk['status'] = $bulktatus;
					$bulk['fileName'] = $response->lista[$pos]->nombreArchivo;
					$bulk['ticketId'] = $response->lista[$pos]->idTicket;
					$bulk['bulkId'] = $response->lista[$pos]->idLote;
					$bulk['loadDate'] = $response->lista[$pos]->fechaCarga;
					$pendingBulkList[] = (object) $bulk;
				}
				break;
				case -15:
					$this->response->code = 0;
					break;
		}

		$this->response->data->pendingBulk = (object) $pendingBulkList;

		return $this->responseToTheView(lang('GEN_GET_PEN_BULK'));
	}
	/**
	 * @info obtiene lista de sucursales
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 19th, 2019
	 */
	public function callWs_GetBranchOffices_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: GetBranchOffices Method Initialized');

		$this->className = 'com.novo.objects.MO.ListadoSucursalesMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Carga de lotes';
		$this->dataAccessLog->operation = 'Obtener sucursales';

		$select = isset($dataRequest->select);
		unset($dataRequest->select);
		$this->dataRequest = new stdClass();
		$this->dataRequest->idOperation = 'getConsultarSucursales';
		$this->dataRequest->paginaActual = '1';
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->lista = [
			[
				'rif' => $this->session->enterpriseInf->idFiscal
			]
		];

		$response = $this->sendToService('GetBranchOffices');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				if($select && count($response->lista) > 1) {
					$branchOffice[] = (object) [
						'key' => '',
						'text' => lang('BULK_SELECT_BRANCH_OFFICE')
					];
				}

				foreach($response->lista AS $pos => $branchs) {
					$branch = [];
					if($select) {
						$branch['key'] = $response->lista[$pos]->cod;
						$branch['text'] = ucfirst(mb_strtolower($response->lista[$pos]->nomb_cia));
						$branchOffice[] = (object) $branch;
						continue;
					}
					$branch['idFiscal'] = $response->lista[$pos]->rif;
					$branch['name'] = mb_strtoupper($response->lista[$pos]->nomb_cia);
					$branchOffice[] = (object) $branch;
				}
			break;
		}

		if($this->isResponseRc != 0) {
			$this->response->code = 1;
			$branchOffice[] = (object) [
				'key' => '',
				'text' => lang('RESP_TRY_AGAIN')
			];
		}

		$this->response->data->branchOffices = (object) $branchOffice;

		return $this->responseToTheView('GetBranchOffices');
	}
	/**
	 * @info Método para obtener los tipos de lte asociados a un programa
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 8th, 2019
	 */
	public function callWs_getTypeLots_Bulk()
	{
		log_message('INFO', 'NOVO Bulk Model: getTypeLots Method Initialized');

		$this->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar Lotes';
		$this->dataAccessLog->operation = 'Obtener tipos lote';

		$this->dataRequest->idOperation = 'consultarTipoLote';
		$this->dataRequest->lotesTO = [
			'codProducto' => $this->session->productInf->productPrefix
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName
		];

		$response = $this->sendToService(lang('GEN_GET_TYPE_LOT'));

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$typesLot[] = (object) [
					'key' => '',
					'format' => '',
					'text' => lang('BULK_SELECT_BULK_TYPE')
				];

				foreach($response->lista AS $pos => $types) {
					$type = [];
					$type['key'] = mb_strtoupper($response->lista[$pos]->idTipoLote);
					$type['format'] = ucfirst(mb_strtolower($response->lista[$pos]->formato));
					$type['text'] = ucfirst(mb_strtolower($response->lista[$pos]->tipoLote));
					$typesLot[] = (object) $type;
				}

			break;
		}

		if($this->isResponseRc != 0) {
			$this->response->code = 1;
			$typesLot[] = (object) [
				'format' => '',
				'key' => '',
				'text' => lang('RESP_TRY_AGAIN')
			];
		}

		$this->response->data->typesLot = (object) $typesLot;

		return $this->responseToTheView(lang('GEN_GET_TYPE_LOT'));
	}
	/**
	 * @info Método para cargar lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 10th, 2019
	 */
	public function callWs_LoadBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: LoadBulk Method Initialized');

		$moveFile = TRUE;
		$this->sendFile($dataRequest->fileName, lang('GEN_LOAD_BULK'));

		if ($this->isResponseRc === 0) {
			$this->className = 'com.novo.objects.MO.ConfirmarLoteMO';
			$this->dataAccessLog->modulo = 'Lotes';
			$this->dataAccessLog->function = 'Cargar Lotes';
			$this->dataAccessLog->operation = 'Mover Archivo';

			$this->dataRequest->idOperation = 'cargarArchivo';
			$this->dataRequest->lotesTO = [
				'codProducto' => $this->session->productInf->productPrefix,
				'formato' => $dataRequest->fileExt,
				'nombre' => $dataRequest->rawName,
				'nombreArchivo' => $dataRequest->fileName,
				'idEmpresa' => $this->session->enterpriseInf->idFiscal,
				'codCia' => $this->session->enterpriseInf->enterpriseCode,
				'sucursal' => isset($dataRequest->branchOffice) ? $dataRequest->branchOffice : '',
				'idTipoLote' => $dataRequest->typeBulk,
				'usuario' => $this->userName,
				'formatoLote' => $dataRequest->formatBulk,
			];
			$this->dataRequest->usuario = [
				'userName' => $this->userName
			];

			$response = $this->sendToService(lang('GEN_LOAD_BULK'));
			$respLoadBulk = FALSE;

			switch ($this->isResponseRc) {
				case 0:
					$this->response->msg = novoLang(lang('BULK_SUCCESS'), substr($dataRequest->rawName, 0, 15).'...');
					$this->response->icon = lang('GEN_ICON_SUCCESS');
					$this->response->data['btn1']['link'] = base_url('cargar-lotes');
					$respLoadBulk = TRUE;
					break;
				case -108:
				case -109:
				case -256:
				case -21:
					$this->response->msg = lang('BULK_NO_LOAD');
					$this->response->data['btn1']['link'] = base_url('cargar-lotes');
					$respLoadBulk = TRUE;
					break;
				case -280:
					$this->response->msg = lang('BULK_INCOMPATIBLE_FILE');
					$respLoadBulk = TRUE;
					break;
				case -128:
					$code = 3;
					$errorsHeader = $response->erroresFormato->erroresEncabezado->errores;
					$errorsFields = $response->erroresFormato->erroresRegistros;
					$errorsList = [];

					if(!empty($errorsHeader)) {
						foreach($errorsHeader AS $errors) {
							$errorsList['header'][] = ucfirst(mb_strtolower($errors));
						}

					}

					if(!empty($errorsFields)) {
						foreach($errorsFields AS $errors) {
							$name = ucfirst(mb_strtolower(str_replace(',', ':', trim($errors->nombre))));
							foreach($errors->errores AS $item) {
								$errorsList['fields'][$name][] = ucfirst(mb_strtolower($item));
							}
						}
					}

					$this->response->msg = $errorsList;
					$respLoadBulk = TRUE;
					break;
			}

			if($respLoadBulk) {
				$this->response->code = isset($code) ? $code : 2;
				$this->response->title = lang('BULK_TITLE_PAGE');
				if($this->isResponseRc != 0) {
					$this->response->data['btn1']['action'] = 'close';
				}
			}
		} else {
			$this->response->code = 2;
			$this->response->title = lang('BULK_TITLE_PAGE');
			$this->response->msg = lang('BULK_FILE_NO_MOVE');
			$this->response->data['btn1']['action'] = 'close';
		}

		return $this->responseToTheView(lang('GEN_LOAD_BULK'));
	}
	/**
	 * @info Elimina un lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 18th, 2019
	 */
	public function callWs_DeleteNoConfirmBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: DeleteNoConfirmBulk Method Initialized');

		$this->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar lotes';
		$this->dataAccessLog->operation = 'Eliminar lote no confirmado';

		unset($dataRequest->modalReq);
		$this->dataRequest->idOperation = 'eliminarLoteNoConfirmado';
		$this->dataRequest->lotesTO = [
			'idTicket' => $dataRequest->bulkTicked,
			'idLote' => $dataRequest->bulkId
		];
		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->passWord)
		);
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => md5($password)
		];

		$response = $this->sendToService('DeleteNoConfirmBulk');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->cod = 0;
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = novoLang(lang('BULK_DELETE_SUCCESS'), $dataRequest->bulkTicked);
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
				break;
			case -1:
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = lang('RESP_PASSWORD_NO_VALID');
				$this->response->icon = lang('GEN_ICON_WARNING');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
				break;
		}

		return $this->responseToTheView('DeleteNoConfirmBulk');
	}
	/**
	 * @info obtener el detalle de un lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 1th, 2019
	 */
	public function callWs_GetDetailBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: GetDetailBulk Method Initialized');

		$this->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar Lotes';
		$this->dataAccessLog->operation = 'Detalle del lote';

		$this->dataRequest->idOperation = 'verDetalleBandeja';
		$this->dataRequest->lotesTO = [
			'idTicket' => $dataRequest->bulkTicked
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName
		];

		$response = $this->sendToService(lang('GEN_DETAIL_BULK'));
		$respLoadBulk = FALSE;
		$detailBulk = [
			'idFiscal' => '',
			'enterpriseName' => '',
			'bulkType' => '',
			'bulkNumber' => '',
			'totaRecords' => '',
			'amount' => '',
			'success' => 'Lote cargado exitosamente',
			'errors' => [],
		];
		$bulkConfirmInfo = new stdClass();

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$detailBulk['idFiscal'] = $response->lotesTO->idEmpresa;
				$detailBulk['enterpriseName'] = mb_strtoupper($response->lotesTO->nombreEmpresa);
				$detailBulk['bulkType'] = $response->lotesTO->tipoLote;
				$detailBulk['bulkNumber'] = $response->lotesTO->numLote;
				$detailBulk['totaRecords'] = $response->lotesTO->cantRegistros;
				$detailBulk['amount'] = $response->lotesTO->monto;
				$detailBulk['bulkTicked'] = $response->lotesTO->idTicket;

				if(!empty($response->lotesTO->mensajes)) {
					foreach($response->lotesTO->mensajes AS $pos => $msg) {
						$error['line'] = 'Línea: '.$msg->linea;
						$error['msg'] = ucfirst(mb_strtolower($msg->mensaje));
						$error['detail'] = '('.$msg->detalle.')';
						$detailBulk['errors'][] = (object) $error;
					}
				}

				unset($response->lotesTO->mensajes);
				$bulkConfirmInfo = $response->lotesTO;
				$this->session->set_flashdata('bulkConfirmInfo', $bulkConfirmInfo);
				$this->session->set_flashdata($dataRequest->bulkView, TRUE);
			break;
		}

		$this->response->data->detailBulk = (object) $detailBulk;

		return $this->responseToTheView(lang('GEN_DETAIL_BULK'));
	}
	/**
	 * @info Confirma un lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 18th, 2019
	 */
	public function callWs_ConfirmBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: ConfirmBulk Method Initialized');

		$this->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Validar Lote';
		$this->dataAccessLog->operation = 'Confirmar Lote';

		$bulkConfirmInfo = $this->session->flashdata('bulkConfirmInfo');
		$bulkConfirmInfo->lineaEmbozo1 = !isset($dataRequest->enbLine1) ?: $dataRequest->enbLine1;
		$bulkConfirmInfo->lineaEmbozo2 = !isset($dataRequest->enbLine2) ?: $dataRequest->enbLine2;
		$bulkConfirmInfo->conceptoAbono = !isset($dataRequest->paymentConcept) ?: $dataRequest->paymentConcept;
		$bulkConfirmInfo->codCia = $this->session->enterpriseInf->idFiscal;

		$this->dataRequest->idOperation = $bulkConfirmInfo->idTipoLote == 'L' && $this->country != 'Ec-bp' ? 'reprocesarLoteGeneral' :'confirmarLote';
		$this->dataRequest->lotesTO = $bulkConfirmInfo;
		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->passWord)
		);
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => md5($password),
			'codigoGrupo' => $this->session->enterpriseInf->enterpriseGroup
		];

		$response = $this->sendToService(lang('GEN_CONFIRM_BULK'));

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = novolang(lang('BULK_CONFIRM_SUCCESS'), $bulkConfirmInfo->numLote);
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data['btn1']['link'] = base_url(lang('GEN_LINK_BULK_AUTH'));
				break;
			case -1:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = lang('RESP_PASSWORD_NO_VALID');
				$this->response->data['btn1']['action'] = 'close';
				break;
			case -142:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = lang('BULK_CONFIRM_FAIL');
				$this->response->data['btn1']['link'] = 'cargar-lotes';
				break;
		}

		if($this->isResponseRc != 0) {
			$this->session->set_flashdata('bulkConfirmInfo', $bulkConfirmInfo);
		}

		return $this->responseToTheView(lang('GEN_CONFIRM_BULK'));
	}
	/**
	 * @info Obtiene la lista de lotes por autorizar
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 26th, 2019
	 */
	public function callWs_AuthorizeBulkList_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: AuthorizeBulkList Method Initialized');

		$this->className = 'com.novo.objects.TOs.LoteTO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Autorización de lotes';
		$this->dataAccessLog->operation = 'Lista de lotes por autorizar';

		$this->dataRequest->idOperation = 'cargarAutorizar';
		$this->dataRequest->accodcia = $this->session->enterpriseInf->enterpriseCode;
		$this->dataRequest->accodgrupo = $this->session->enterpriseInf->enterpriseGroup;
		$this->dataRequest->acrif = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->actipoproducto = $this->session->productInf->productPrefix;
		$this->dataRequest->accodusuarioc = $this->userName;


		$response = $this->sendToService(lang('GEN_AUTHORIZE_BULK_LIST'));

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$response = $this->callWs_MakeBulkList_Bulk($response);
				break;
			case -38:
				$this->response->code = 3;
				$this->response->title = 'Autorización de lotes';
				$this->response->msg = lang('RESP_NO_LIST');
				break;
		}

		$this->response->data->signBulk = (object) $response->signBulk;
		$this->response->data->authorizeBulk = (object) $response->authorizeBulk;
		$this->response->data->authorizeAttr = $response->authorizeAttr;

		return $this->responseToTheView(lang('GEN_AUTHORIZE_BULK_LIST'));
	}
	/**
	 * @info Firma lista de lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 28th, 2019
	 */
	public function callWs_SignBulkList_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: SignBulkList Method Initialized');

		$this->className = 'com.novo.objects.MO.ListadoLotesMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Autorización de lotes';
		$this->dataAccessLog->operation = 'Firmar lote';

		$signListBulk = [];

		foreach($dataRequest->bulk AS $bulkInfo) {
			$bulkInfo = json_decode($bulkInfo);
			$bulkList  = [
				'acidlote' => $bulkInfo->bulkId,
				'accodcia' => $this->session->enterpriseInf->enterpriseCode,
				'accodgrupo' => $this->session->enterpriseInf->enterpriseGroup,
				'actipoproducto' => $this->session->productInf->productPrefix
			];
			$signListBulk[] = $bulkList;
		}

		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->password)
		);
		$this->dataRequest->idOperation = 'firmarLote';
		$this->dataRequest->lista = $signListBulk;
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => md5($password)
		];

		$this->sendToService(lang('GEN_SIGN_BULK_LIST'));

		switch ($this->isResponseRc) {
			case 0:
				$this->response->title = lang('BULK_SIGN_TITLE');
				$this->response->msg = 'Lote firmado exitosamente';
				$this->response->data['btn1']['link'] = 'lotes-autorizacion';
				break;
			case -1:
				$this->response->title = lang('BULK_SIGN_TITLE');
				$this->response->msg = lang('RESP_PASSWORD_NO_VALID');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->responseToTheView(lang('GEN_SIGN_BULK_LIST'));
	}
	/**
	 * @info Firma lista de lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 28th, 2019
	 */
	public function callWs_DeleteConfirmBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: DeleteConfirmBulk Method Initialized');

		$this->className = 'com.novo.objects.MO.AutorizarLoteMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Autorización de lotes';
		$this->dataAccessLog->operation = 'Eliminar lote';

		$deleteListBulk = [];

		foreach($dataRequest->bulk AS $bulkInfo) {
			$bulkInfo = json_decode($bulkInfo);
			$bulkList  = [
				'acrif' => $this->session->enterpriseInf->idFiscal,
				'acidlote' => $bulkInfo->bulkId,
				'acnumlote' => $bulkInfo->bulkNumber,
				'ctipolote' => $bulkInfo->bulkIdType
			];
			$deleteListBulk[] = $bulkList;
		}

		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->password)
		);
		$this->dataRequest->idOperation = 'eliminarLotesPorAutorizar';
		$this->dataRequest->listaLotes = [
			'lista' => $deleteListBulk
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => md5($password)
		];

		$this->sendToService(lang('GEN_DELETE_BULK'));

		switch ($this->isResponseRc) {
			case 0:
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = 'Lote eliminado exitosamente';
				$this->response->data['btn1']['link'] = 'lotes-autorizacion';
				break;
			case -22:
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = lang('RESP_PASSWORD_NO_VALID');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->responseToTheView(lang('GEN_DELETE_BULK'));
	}
	/**
	 * @info Ver el detalle de los lotes confirmados
	 * @author J. Enrique Peñaloza Piñero
	 * @date February 09th, 2020
	 */
	public function callWs_ConfirmBulkdetail_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: ConfirmBulkdetail Method Initialized');

		$this->className = 'com.novo.objects.MO.AutorizarLoteMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Autorización de lotes';
		$this->dataAccessLog->operation = 'Ver detalle del lote';

		$this->dataRequest->idOperation = 'detalleLote';
		$this->dataRequest->acidlote = $dataRequest->bulkId;

		$response = $this->sendToService('ConfirmBulkdetail');

		$detailInfo = [
			'fiscalId' => '--',
			'enterpriseName' => '--',
			'bulkType' => '--',
			'bulkTypeText' => '--',
			'bulkNumber' => '--',
			'totalRecords' => '--',
			'loadUserName' => '--',
			'bulkDate' => '--',
			'bulkStatus' => '--',
			'bulkStatusText' => '--',
			'bulkAmount' => '--',
			'bulkHeader' => [],
			'bulkRecords' => [],
		];

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$detailInfo['fiscalId'] = $response->acrif;
				$detailInfo['enterpriseName'] = mb_strtoupper(mb_strtolower($response->acnomcia));
				$detailInfo['bulkType'] = $response->ctipolote;
				$detailInfo['bulkTypeText'] = mb_strtoupper(mb_strtolower($response->acnombre));
				$detailInfo['bulkNumber'] = $response->acnumlote;
				$detailInfo['totalRecords'] = $response->ncantregs;
				$detailInfo['loadUserName'] = mb_strtoupper(mb_strtolower($response->accodusuarioc));
				$detailInfo['bulkDate'] = $response->dtfechorcarga;
				$detailInfo['bulkStatus'] = $response->cestatus;
				$detailInfo['bulkStatusText'] = ucfirst(mb_strtolower($response->status));
				$detailInfo['bulkAmount'] = $response->montoNeto;

				$response->ctipolote = 'L';
				switch($response->ctipolote) {
					case '1':
						if(isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
							$bulkRecordsHeader = ['DNI', 'Nombres y apellidos', 'Estado'];
							$detailInfo['bulkHeader'] = $bulkRecordsHeader;

							foreach($response->registrosLoteEmision AS $records) {
								$record = new stdClass();
								foreach($records AS $pos => $value) {
									switch ($pos) {
										case 'idExtPer':
											$record->cardHoldId = $value;
											break;
										case 'nombres':
											$record->cardHoldName = ucwords(mb_strtolower($value));
											break;
										case 'apellidos':
											$record->cardHoldLastName = ucwords(mb_strtolower($value));
											break;
										case 'status':
											$status = [
												'0' => 'En proceso',
												'1' => 'Procesado',
												'7' => 'Rechazado',
											];
											$record->bulkstatus = $status[$value];
											break;
									}
								}
								$record->cardHoldName = $record->cardHoldName.' '.$record->cardHoldLastName;
								unset($record->cardHoldLastName);
								array_push(
									$detailInfo['bulkRecords'],
									$record
								);
							}
						}
						break;
					case '2':
					case '5':
					case 'L':
						if(isset($response->registrosLoteRecarga) && count($response->registrosLoteRecarga) > 0) {
							$bulkRecordsHeader = ['DNI', 'Monto', 'Número de cuenta'];

							if($response->ctipolote == '5' || $response->ctipolote == 'L') {
								$bulkRecordsHeader = ['DNI', 'Monto', 'Número de cuenta', 'Estado'];
							}

							$detailInfo['bulkHeader'] = $bulkRecordsHeader;

							foreach($response->registrosLoteRecarga AS $records) {
								$record = new stdClass();
								foreach($records AS $pos => $value) {
									switch ($pos) {
										case 'id_ext_per':
											$record->cardHoldId = $value;
											break;
											case 'monto':
												$record->cardHoldAmount = $value;
											break;
										case 'nro_cuenta':
											$record->cardHoldAccount = $value;
											break;
										case 'status':
											if($response->ctipolote == '5') {
												$status = [
													'3' => 'En proceso',
													'6' => 'Procesada',
													'7' => 'Rechazado',
												];
											}

											if($response->ctipolote == 'L') {
												$status = [
													'0' => 'Pendiente',
													'1' => 'Procesada',
													'2' => 'Inválida',
													'7' => 'Rechazado',
												];
											}
											$record->bulkstatus = $status[$value];
											break;
									}
								}
								array_push(
									$detailInfo['bulkRecords'],
									$record
								);
							}
						}
						break;
				}
				break;
		}

		$this->response->data->bulkInfo = (object) $detailInfo;

		return $this->responseToTheView('ConfirmBulkdetail');

	}
	/**
	 * @info Firma lista de lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 28th, 2019
	 */
	public function callWs_AuthorizeBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: AuthorizeBulk Method Initialized');

		$this->className = 'com.novo.objects.TOs.OrdenServicioTO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Autorización de lotes';
		$this->dataAccessLog->operation = 'Calcular orden de servicio';

		$signListBulk = [];

		foreach($dataRequest->bulk AS $bulkInfo) {
			$bulkInfo = json_decode($bulkInfo);
			$bulkList  = [
				'acidlote' => $bulkInfo->bulkId
			];
			$signListBulk[] = $bulkList;
		}

		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->password)
		);
		$this->dataRequest->idOperation = 'calcularOS';
		$this->dataRequest->datosEmpresa = [
			'acrif' => $this->session->enterpriseInf->idFiscal
		];
		$this->dataRequest->acprefix = $this->session->productInf->productPrefix;
		$this->dataRequest->acUsuario = $this->userName;
		$this->dataRequest->tipoOrdeServicio = $dataRequest->typeOrder;
		$this->dataRequest->nuevoIva = FALSE;
		$this->dataRequest->medioPago = [
			'idPago' => '',
			'descripcion' => 'Deposito y Transferencia'
		];
		$this->dataRequest->lotes = $signListBulk;
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => md5($password)
		];

		$response = $this->sendToService(lang('GEN_AUTH_BULK'));

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = base_url('lotes-calcular-orden');
				$serviceOrdersList = [];

				foreach($response->lista AS $dataOrder) {
					$bulkList = [];
					foreach($dataOrder AS $key => $value) {
						switch ($key) {
							case 'idOrdenTemp':
								$serviceOrders['tempOrderId'] = $value;
								break;
							case 'montoComision':
								$serviceOrders['commisAmount'] = $value;
								break;
							case 'montoIVA':
								$serviceOrders['VatAmount'] = $value;
								break;
							case 'montoOS':
								$serviceOrders['soAmount'] = $value;
								break;
							case 'montoTotal':
								$serviceOrders['totalAmount'] = $value;
								break;
							case 'montoDeposito':
								$serviceOrders['depositedAmount'] = $value;
								break;
							case 'lotes':
								$serviceOrders['bulk'] = [];
								foreach($value AS $bulk) {
									$bulkList['bulkNumber'] = $bulk->acnumlote;
									$bulkList['bulkLoadDate'] = $bulk->dtfechorcarga;
									$bulkList['bulkLoadType'] = ucfirst(mb_strtolower($bulk->acnombre));
									$bulkList['bulkRecords'] = $bulk->ncantregs;
									$bulkList['bulkStatus'] = ucfirst(mb_strtolower($bulk->status));
									$bulkList['bulkAmount'] = floatval($bulk->montoRecarga);
									$bulkList['bulkCommisAmount'] = floatval($bulk->montoComision);
									$bulkList['bulkTotalAmount'] = floatval($bulk->montoRecarga) + floatval($bulk->montoComision);
									$serviceOrders['bulk'][] = (object) $bulkList;
								}
								break;
						}
					}

					$serviceOrdersList[] = (object) $serviceOrders;
				}

				$bulkNotBillable = [];
				if(count($response->lotesNF) > 0) {
					foreach($response->lotesNF AS $notBillable) {
						$bulkList = [];
						foreach($notBillable AS $key => $value) {
							switch ($value) {
								case 'acidlote':
									$bulkList['bulkId'] = $value;
									break;
								case 'acnumlote':
									$bulkList['bulkNumber'] = $value;
									break;
								case 'dtfechorcarga':
									$bulkList['bulkLoadDate'] = $value;
									break;
								case 'acnombre':
									$bulkList['bulkLoadType'] = ucfirst(mb_strtolower($value));
									break;
								case 'ncantregs':
									$bulkList['bulkRecords'] = $value;
									break;
								case 'status':
									$bulkList['bulkStatus'] = ucfirst(mb_strtolower($value));
									break;
							}
						}

						$bulkNotBillable[] = (object) $bulkList;
					}
				}

				$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
				$this->session->set_flashdata('bulkNotBillable', $bulkNotBillable);
				break;
			case -1:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('RESP_PASSWORD_NO_VALID');
				$this->response->data['btn1']['action'] = 'close';
				break;
			case -59:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('RESP_AUTH_ORDER_SERV');
				$this->response->data['btn1']['action'] = 'close';
				break;
			case 100:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = novoLang(lang('BULK_AUTH_SUCCESS'), $this->userName);
				$this->response->data['btn1']['link'] = 'lotes-autorizacion';
				break;
		}

		return $this->responseToTheView(lang('GEN_AUTH_BULK'));
	}
	/**
	 * @info Genera orden de servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date January 05th, 2019
	 */
	public function callWs_ServiceOrder_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: ServiceOrder Method Initialized');

		$this->className = 'com.novo.objects.MO.ListadoOrdenServicioMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Orden de servicio';
		$this->dataAccessLog->operation = 'Generar orden de servicio';

		$listTemp = [];
		$listTempNoBill = [];

		if(isset($dataRequest->tempOrders)) {
			$tempOrders = explode(',', $dataRequest->tempOrders);
			array_pop($tempOrders);
			foreach($tempOrders AS $temp) {
				$list['idOrdenTemp'] = $temp;
				$list['acprefix'] = $this->session->productInf->productPrefix;
				$listTemp[] = (object) $list;
			}
		}

		if(isset($dataRequest->bulkNoBill)) {
			$bulkNoBill = explode(',', $dataRequest->bulkNoBill);
			array_pop($bulkNoBill);
			foreach($bulkNoBill AS $temp) {
				$listNoBill['acidlote'] = $temp;
				$listNoBill['acprefix'] = $this->session->productInf->productPrefix;
				$listTempNoBill[] = (object) $listNoBill;
			}
		}

		$this->dataRequest->idOperation = 'generarOS';
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->lista = $listTemp;
		$this->dataRequest->lotesNF = $listTempNoBill;
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'codigoGrupo' => $this->session->enterpriseInf->enterpriseGroup
		];

		$response = $this->sendToService('ServiceOrder');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = lang('GEN_LINK_CONS_ORDERS_SERV');

				foreach($response->lista AS $list) {
					$orderList = [];
					foreach($list AS $key => $value) {
						switch ($key) {
							case 'idOrden':
								$serviceOrders['OrderNumber'] = $value;
								break;
							case 'fechaGeneracion':
								$serviceOrders['Orderdate'] = $value;
								break;
							case 'montoComision':
								$serviceOrders['OrderCommission'] = $value;
								break;
							case 'montoIVA':
								$serviceOrders['OrderTax'] = $value;
								break;
							case 'montoOS':
								$serviceOrders['OrderAmount'] = $value;
								break;
							case 'montoDeposito':
								$serviceOrders['OrderDeposit'] = $value;
								break;
							case 'lotes':
								$serviceOrders['bulk'] = [];
								foreach($value AS $bulk) {
									$bulkList['bulkNumber'] = $bulk->acnumlote;
									$bulkList['bulkLoadDate'] = $bulk->dtfechorcarga;
									$bulkList['bulkLoadType'] = ucfirst(mb_strtolower($bulk->acnombre));
									$bulkList['bulkRecords'] = $bulk->ncantregs;
									$bulkList['bulkStatus'] = ucfirst(mb_strtolower($bulk->status));
									$bulkList['bulkAmount'] = floatval($bulk->montoRecarga);
									$bulkList['bulkCommisAmount'] = floatval($bulk->montoComision);
									$bulkList['bulkTotalAmount'] = floatval($bulk->montoRecarga) + floatval($bulk->montoComision);
									$serviceOrders['bulk'][] = (object) $bulkList;
								}
								break;
						}
					}

					$serviceOrdersList[] = (object) $serviceOrders;
				}

				$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
				break;

			case -5:
				$this->response->title = 'Generar orden de servicio';
				$this->response->msg = 'No fue posible generar la orden de servicio';
				$this->response->icon = lang('GEN_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		$serviceOrdersList = $this->session->flashdata('serviceOrdersList');
		$bulkNotBillable = $this->session->flashdata('bulkNotBillable');
		$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
		$this->session->set_flashdata('bulkNotBillable', $bulkNotBillable);

		return $this->responseToTheView('ServiceOrder');
	}
	/**
	 * @info Cancela calculo de orden de servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date January 05th, 2019
	 */
	public function callWs_CancelServiceOrder_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: cancelServiceOrder Method Initialized');

		$this->className = 'com.novo.objects.MO.ListadoOrdenServicioMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Orden de servicio';
		$this->dataAccessLog->operation = 'Cancelar orden de servicio';

		$listTemp = [];
		$listTempNoBill = [];

		if(isset($dataRequest->tempOrders)) {
			$tempOrders = explode(',', $dataRequest->tempOrders);
			array_pop($tempOrders);
			foreach($tempOrders AS $temp) {
				$list['idOrdenTemp'] = $temp;
				$listTemp[] = (object) $list;
			}
		}

		$this->dataRequest->idOperation = 'cancelarOS';
		$this->dataRequest->lista = $listTemp;
		$this->dataRequest->lotesNF[] = [
			'accodcia' => $this->session->enterpriseInf->enterpriseCode,
			'accodgrupo' => $this->session->enterpriseInf->enterpriseGroup,
			'acrif' => $this->session->enterpriseInf->idFiscal,
			'actipoproducto' => $this->session->productInf->productPrefix,
			'accodusuarioc' => $this->userName
		];

		$response = $this->sendToService('cancelServiceOrder');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$responseList = new stdClass();
				$responseList->code = 0;
				$responseList->data = $this->callWs_MakeBulkList_Bulk($response);
				$this->session->set_flashdata('bulkList', $responseList);
				$this->response->data = base_url('lotes-autorizacion');
				break;
		}

		if($this->isResponseRc != 0) {
			$serviceOrdersList = $this->session->flashdata('serviceOrdersList');
			$bulkNotBillable = $this->session->flashdata('bulkNotBillable');
			$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
			$this->session->set_flashdata('bulkNotBillable', $bulkNotBillable);
		}

		return $this->responseToTheView('cancelServiceOrder');
	}
	/**
	 * @info Arma la respuesta para la lista de lotes por autorizar
	 * @author J. Enrique Peñaloza Piñero
	 * @date January 08th, 2019
	 */
	private function callWs_MakeBulkList_Bulk($bulkList)
	{
		log_message('INFO', 'NOVO Bulk Model: MakeBulkList Method Initialized');

		$signBulk = [];
		$authorizeBulk = [];
		$authorizeAttr = [];
		$allBulk = 'no-select-checkbox';

		if(verifyDisplay('body', lang('GEN_AUTHORIZE_BULK_LIST'), lang('GEN_TAG_ALL_BULK'))) {
			$allBulk = 'toggle-all';
		}

		$sign = TRUE;
		$auth = TRUE;
		$order = (int) $bulkList->usuario->orden;

		if($order == 1) {
			$auth = FALSE;
		}

		if($order > 1) {
			$sign = FALSE;
		}

		if(!empty($bulkList->listaPorFirmar)) {
			foreach($bulkList->listaPorFirmar AS $bulk) {
				$detailBulk['idBulk'] = $bulk->acidlote;
				$detailBulk['bulkNumber'] = $bulk->acnumlote;
				$detailBulk['loadDate'] = $bulk->dtfechorcarga;
				$detailBulk['idType'] = $bulk->ctipolote;
				$detailBulk['type'] = ucwords(mb_strtolower(substr($bulk->acnombre, 0, 20)));
				$detailBulk['records'] = $bulk->ncantregs;
				$detailBulk['amount'] = $bulk->nmonto;
				$detailBulk['selectBulk'] = $sign ? '' : 'no-select-checkbox';
				$signBulk[] = (object) $detailBulk;
			}
		}

		if(!empty($bulkList->listaPorAutorizar)) {
			foreach($bulkList->listaPorAutorizar AS $bulk) {
				$detailBulk['idBulk'] = $bulk->acidlote;
				$detailBulk['bulkNumber'] = $bulk->acnumlote;
				$detailBulk['loadDate'] = $bulk->dtfechorcarga;
				$detailBulk['idType'] = $bulk->ctipolote;
				$detailBulk['type'] = ucwords(mb_strtolower(substr($bulk->acnombre, 0, 20)));
				$detailBulk['records'] = $bulk->ncantregs;
				$detailBulk['amount'] = $bulk->nmonto;
				$detailBulk['selectBulk'] = $auth ? '' : 'no-select-checkbox';
				$listAth = mb_strtoupper($bulk->accodusuarioa);
				$listAth = explode(',', $listAth);
				$detailBulk['selectRow'] = in_array($this->userName, $listAth) ? 'no-select-checkbox' : '';
				$detailBulk['selectRowContent'] = in_array($this->userName, $listAth) ? 'TRUE' : '';
				$authorizeBulk[] = (object) $detailBulk;
			}
		}

		$authorizeAttr = (object) [
			'toPAy' => $bulkList->ordenXPagar,
			'allBulk' => $allBulk,
			'sign' => $sign,
			'auth' => $auth
		];
		$response = new stdClass();
		$response->signBulk = (object) $signBulk;
		$response->authorizeBulk = (object) $authorizeBulk;
		$response->authorizeAttr = $authorizeAttr;

		return $response;
	}
}
