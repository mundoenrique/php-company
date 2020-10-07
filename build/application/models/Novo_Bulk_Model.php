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
	public function callWs_GetPendingBulk_Bulk()
	{
		log_message('INFO', 'NOVO Bulk Model: getPendingBulk Method Initialized');

		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar Lotes';
		$this->dataAccessLog->operation = 'Lotes por confirmar';

		$this->dataRequest->idOperation = 'buscarLotesPorConfirmar';
		$this->dataRequest->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataRequest->lotesTO = [
			'idEmpresa' => $this->session->enterpriseInf->idFiscal,
			'codCia' => $this->session->enterpriseInf->enterpriseCode,
			'codProducto' => $this->session->productInf->productPrefix
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName
		];

		$response = $this->sendToService('callWs_GetPendingBulk');
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
							$bulk['statusColor'] = ' bg-being-validated';
							$bulk['statusText'] = lang('BULK_VALIDATING');
							break;
						case '1':
							$bulk['statusPr'] = 'status-pr ';
							$bulk['statusColor'] = ' bg-will-processed';
							$bulk['statusText'] = lang('BULK_VALID');
							break;
						case '5':
							$bulk['statusPr'] = '';
							$bulk['statusColor'] = 'bg-not-processed';
							$bulk['statusText'] = lang('BULK_NO_VALID');
							break;
						case '6':
							$bulk['statusPr'] = 'status-pr ';
							$bulk['statusColor'] = ' bg-will-not-processed';
							$bulk['statusText'] = lang('BULK_VALID');
							break;
					}

					$bulk['status'] = $bulktatus;
					$fileName = explode('.', $response->lista[$pos]->nombreArchivo);
					$bulk['fileName'] = substr_replace($fileName[0], '', 0, strlen($this->countryUri.'_'));
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

		return $this->responseToTheView('callWs_GetPendingBulk');
	}
	/**
	 * @info Método para obtener los tipos de lte asociados a un programa
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 8th, 2019
	 */
	public function callWs_GetTypeLots_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: getTypeLots Method Initialized');

		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar Lotes';
		$this->dataAccessLog->operation = 'Obtener tipos lote';

		$this->dataRequest->idOperation = 'consultarTipoLote';
		$this->dataRequest->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataRequest->lotesTO = [
			'codProducto' => $this->session->productInf->productPrefix
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName
		];


		if($dataRequest->newGet == 0) {
			$response = $this->sendToService('callWs_getTypeLots');
		} else {
			$dataRequest->rc = $dataRequest->newGet;
			$this->makeAnswer($dataRequest, 'callWs_getTypeLots');
		}

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

		return $this->responseToTheView('callWs_getTypeLots');
	}
	/**
	 * @info Método para cargar lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 10th, 2019
	 */
	public function callWs_LoadBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: LoadBulk Method Initialized');

		$this->sendFile($dataRequest->fileName, 'LoadBulk');

		if ($this->isResponseRc === 0) {
			$this->dataAccessLog->modulo = 'Lotes';
			$this->dataAccessLog->function = 'Cargar Lotes';
			$this->dataAccessLog->operation = 'Mover Archivo';

			$this->dataRequest->idOperation = 'cargarArchivo';
			$this->dataRequest->className = 'com.novo.objects.MO.ConfirmarLoteMO';
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

			$response = $this->sendToService('callWs_LoadBulk');
			$respLoadBulk = FALSE;

			switch ($this->isResponseRc) {
				case 0:
					$this->response->msg = lang('BULK_SUCCESS');
					$this->response->icon = lang('CONF_ICON_SUCCESS');
					$this->response->data['btn1']['link'] = lang('GEN_LINK_BULK_LOAD');
					$respLoadBulk = TRUE;
					break;
				case -108:
				case -109:
				case -256:
				case -21:
					$this->response->msg = lang('BULK_NO_LOAD');
					$this->response->data['btn1']['link'] = lang('GEN_LINK_BULK_LOAD');
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

		return $this->responseToTheView('callWs_LoadBulk');
	}
	/**
	 * @info Elimina un lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 18th, 2019
	 */
	public function callWs_DeleteNoConfirmBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: DeleteNoConfirmBulk Method Initialized');

		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar lotes';
		$this->dataAccessLog->operation = 'Eliminar lote no confirmado';

		unset($dataRequest->modalReq);
		$this->dataRequest->idOperation = 'eliminarLoteNoConfirmado';
		$this->dataRequest->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataRequest->lotesTO = [
			'idTicket' => $dataRequest->bulkTicked,
			'idLote' => $dataRequest->bulkId
		];
		$password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : lang('GEN_GENERIC_PASS');

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = md5($password);
		}

		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => $password
		];

		$response = $this->sendToService('callWs_DeleteNoConfirmBulk');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->cod = 0;
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = novoLang(lang('BULK_DELETE_SUCCESS'), $dataRequest->bulkname);
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
				break;
			case -1:
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
				break;
		}

		return $this->responseToTheView('callWs_DeleteNoConfirmBulk');
	}
	/**
	 * @info obtener el detalle de un lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 1th, 2019
	 */
	public function callWs_GetDetailBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: GetDetailBulk Method Initialized');

		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar Lotes';
		$this->dataAccessLog->operation = 'Detalle del lote';

		$this->dataRequest->idOperation = 'verDetalleBandeja';
		$this->dataRequest->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataRequest->lotesTO = [
			'idTicket' => $dataRequest->bulkTicked
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName
		];

		$response = $this->sendToService('GetDetailBulk');
		$respLoadBulk = FALSE;
		$detailBulk = [
			'idFiscal' => '',
			'enterpriseName' => '',
			'bulkType' => '',
			'bulkNumber' => '',
			'totaRecords' => '',
			'amount' => '',
			'bulkTicked' => '',
			'success' => '',
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
				$detailBulk['success'] = 'Lote cargado exitosamente';

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
			case -437:
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = novoLang(lang('GEN_FAILED_THIRD_PARTY'), $response->msg);
				$this->response->data->resp['btn1']['link'] = lang('GEN_LINK_BULK_LOAD');
			break;
			case -443:
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = lang('BULK_CONFIRM_EXCEED_LIMIT');
				$this->response->data->resp['btn1']['link'] = lang('GEN_LINK_BULK_LOAD');
			break;
		}

		$this->response->data->detailBulk = (object) $detailBulk;

		return $this->responseToTheView('GetDetailBulk');
	}
	/**
	 * @info Confirma un lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 18th, 2019
	 */
	public function callWs_ConfirmBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: ConfirmBulk Method Initialized');

		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Validar Lote';
		$this->dataAccessLog->operation = 'Confirmar Lote';

		$bulkConfirmInfo = $this->session->flashdata('bulkConfirmInfo');
		$bulkConfirmInfo->lineaEmbozo1 = !isset($dataRequest->enbLine1) ?: $dataRequest->enbLine1;
		$bulkConfirmInfo->lineaEmbozo2 = !isset($dataRequest->enbLine2) ?: $dataRequest->enbLine2;
		$bulkConfirmInfo->conceptoAbono = !isset($dataRequest->paymentConcept) ?: $dataRequest->paymentConcept;
		$bulkConfirmInfo->codCia = $this->session->enterpriseInf->enterpriseCode;

		$this->dataRequest->idOperation = $bulkConfirmInfo->idTipoLote == 'L' && lang('CONF_BULK_REPROCESS') == 'ON' ? 'reprocesarLoteGeneral'
			: 'confirmarLote';
		$this->dataRequest->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataRequest->lotesTO = $bulkConfirmInfo;
		$password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : lang('GEN_GENERIC_PASS');

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = md5($password);
		}

		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => $password,
			'codigoGrupo' => $this->session->enterpriseInf->enterpriseGroup
		];

		$response = $this->sendToService('callWs_ConfirmBulk');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = novolang(lang('BULK_CONFIRM_SUCCESS'), $bulkConfirmInfo->numLote);
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$link = $this->verify_access->verifyAuthorization('TEBAUT') ? lang('GEN_LINK_BULK_AUTH') : lang('GEN_LINK_BULK_LOAD');
				$this->response->data['btn1']['link'] = $link;
			break;
			case -1:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -19:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = lang('BULK_CONFIRM_NO_DEAIL');
				$this->response->data['btn1']['link'] = lang('GEN_LINK_BULK_LOAD');
			break;
			case -142:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = lang('BULK_CONFIRM_FAIL');
				$this->response->data['btn1']['link'] = lang('GEN_LINK_BULK_LOAD');
			break;
			case -236:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = lang('BULK_CONFIRM_FAIL_DULPICATE');
				$this->response->data['btn1']['link'] = lang('GEN_LINK_BULK_LOAD');
			break;
			case -436:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = lang('BULK_CONFIRM_FAIL_BANK_RESPONSE');
				$this->response->data['btn1']['link'] = lang('GEN_LINK_BULK_LOAD');
			break;
			case -437:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = novoLang(lang('GEN_FAILED_THIRD_PARTY'), $response->msg);
				$this->response->data->resp['bnt1']['link'] = lang('GEN_LINK_BULK_LOAD');
			break;
			case -438:
				$this->response->code = 0;
				$this->response->title = lang('BULK_CONFIRM_TITLE');
				$this->response->msg = lang('BULK_CONFIRM_DUPLICATE');
				$this->response->data['btn1']['link'] = lang('GEN_LINK_BULK_LOAD');
			break;
		}

		if($this->isResponseRc != 0) {
			$this->session->set_flashdata('bulkConfirmInfo', $bulkConfirmInfo);
		}

		return $this->responseToTheView('callWs_ConfirmBulk');
	}
	/**
	 * @info Obtiene la lista de lotes por autorizar
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 26th, 2019
	 */
	public function callWs_AuthorizeBulkList_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: AuthorizeBulkList Method Initialized');

		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Autorización de lotes';
		$this->dataAccessLog->operation = 'Lista de lotes por autorizar';

		$this->dataRequest->idOperation = 'cargarAutorizar';
		$this->dataRequest->className = 'com.novo.objects.TOs.LoteTO';
		$this->dataRequest->accodcia = $this->session->enterpriseInf->enterpriseCode;
		$this->dataRequest->accodgrupo = $this->session->enterpriseInf->enterpriseGroup;
		$this->dataRequest->acrif = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->actipoproducto = $this->session->productInf->productPrefix;
		$this->dataRequest->accodusuarioc = $this->userName;


		$response = $this->sendToService('callWs_AuthorizeBulkList');
		$response = $this->callWs_MakeBulkList_Bulk($response);

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				break;
			case -38:
				$this->response->code = 3;
				$this->response->title = lang('BULK_AUTHORIZE');
				$this->response->msg = lang('RESP_NO_LIST');
				break;
		}

		$this->response->data->signBulk = $response->signBulk;
		$this->response->data->authorizeBulk = $response->authorizeBulk;
		$this->response->data->authorizeAttr = $response->authorizeAttr;

		return $this->responseToTheView('callWs_AuthorizeBulkList');
	}
	/**
	 * @info Firma lista de lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 28th, 2019
	 */
	public function callWs_SignBulkList_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: SignBulkList Method Initialized');

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

		$password = $this->cryptography->decryptOnlyOneData($dataRequest->pass);

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = md5($password);
		}

		$this->dataRequest->idOperation = 'firmarLote';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoLotesMO';
		$this->dataRequest->lista = $signListBulk;
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => $password
		];

		$this->sendToService('callWs_SignBulkList');

		switch ($this->isResponseRc) {
			case 0:
				$msgREsp = count($signListBulk) > 1 ? lang('BULK_SIGNEDS') : lang('BULK_SIGNED');
				$this->response->title = lang('BULK_SIGN_TITLE');
				$this->response->msg = $msgREsp;
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data['btn1']['link'] = 'lotes-autorizacion';
				break;
			case -1:
				$this->response->title = lang('BULK_SIGN_TITLE');
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->responseToTheView('callWs_SignBulkList');
	}
	/**
	 * @info Firma lista de lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 28th, 2019
	 */
	public function callWs_DeleteConfirmBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: DeleteConfirmBulk Method Initialized');

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

		$password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : lang('GEN_GENERIC_PASS');

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = md5($password);
		}

		$this->dataRequest->idOperation = 'eliminarLotesPorAutorizar';
		$this->dataRequest->className = 'com.novo.objects.MO.AutorizarLoteMO';
		$this->dataRequest->listaLotes = [
			'lista' => $deleteListBulk
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => $password
		];

		$this->sendToService('callWs_DeleteConfirmBulk');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = lang('BULK_DELETED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data['btn1']['link'] = 'lotes-autorizacion';
				break;
			case -16:
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = novoLang(lang('BULK_NOT_DELETED'), $bulkInfo->bulkNumber);
				$this->response->data['btn1']['action'] = 'close';
				break;
			case -1:
			case -22:
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->responseToTheView('callWs_DeleteConfirmBulk');
	}
	/**
	 * @info Firma lista de lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 28th, 2019
	 */
	public function callWs_DisassConfirmBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: DisassConfirmBulk Method Initialized');

		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Autorización de lotes';
		$this->dataAccessLog->operation = 'Desasociar firma';

		$disassListBulk = [];

		foreach($dataRequest->bulk AS $bulkInfo) {
			$bulkInfo = json_decode($bulkInfo);
			$bulkList  = [
				'acidlote' => $bulkInfo->bulkId,
				'accodcia' => $this->session->enterpriseInf->enterpriseCode,
				'accodgrupo' => $this->session->enterpriseInf->enterpriseGroup,
				'actipoproducto' => $this->session->productInf->productPrefix
			];
			$disassListBulk[] = $bulkList;
		}

		$password = $this->cryptography->decryptOnlyOneData($dataRequest->pass);

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = md5($password);
		}

		$this->dataRequest->idOperation = 'desasociarFirma';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoLotesMO';
		$this->dataRequest->lista = $disassListBulk;
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => $password
		];

		$this->sendToService('callWs_DisassConfirmBulk');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->title = lang('BULK_DISASS_TITLE');
				$this->response->msg = lang('BULK_DISASSOCIATED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data['btn1']['link'] = 'lotes-autorizacion';
				break;
			case -16:
				$this->response->title = lang('BULK_DISASS_TITLE');
				$this->response->msg = novoLang(lang('BULK_NOT_DISASS'), $bulkInfo->bulkNumber);
				$this->response->data['btn1']['action'] = 'close';
				break;
			case -1:
			case -22:
				$this->response->title = lang('BULK_DISASS_TITLE');
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->responseToTheView('callWs_DisassConfirmBulk');
	}
	/**
	 * @info Firma lista de lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 28th, 2019
	 */
	public function callWs_AuthorizeBulk_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: AuthorizeBulk Method Initialized');

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

		$password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : lang('GEN_GENERIC_PASS');

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = md5($password);
		}

		$this->dataRequest->idOperation = 'calcularOS';
		$this->dataRequest->className = 'com.novo.objects.TOs.OrdenServicioTO';
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
			'password' => $password
		];

		$response = $this->sendToService('callWs_AuthorizeBulk');

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
								$serviceOrders['commisAmount'] = currencyFormat($value);
							break;
							case 'montoIVA':
								$serviceOrders['VatAmount'] = currencyFormat($value);
							break;
							case 'montoOS':
								$serviceOrders['soAmount'] = currencyFormat($value);
							break;
							case 'montoTotal':
								$serviceOrders['totalAmount'] = currencyFormat($value);
							break;
							case 'montoDeposito':
								$serviceOrders['depositedAmount'] = currencyFormat($value);
							break;
							case 'lotes':
								$serviceOrders['bulk'] = [];
								foreach($value AS $bulk) {
									$bulkList['bulkNumber'] = $bulk->acnumlote;
									$bulkList['bulkLoadDate'] = $bulk->dtfechorcarga;
									$bulkList['bulkLoadType'] = ucfirst(mb_strtolower($bulk->acnombre));
									$bulkList['bulkRecords'] = $bulk->ncantregs;
									$bulkList['bulkStatus'] = ucfirst(mb_strtolower($bulk->status));
									$bulkList['bulkAmount'] = currencyFormat($bulk->montoRecarga);
									$bulkList['bulkCommisAmount'] = currencyFormat($bulk->montoComision);
									$bulkList['bulkTotalAmount'] = currencyFormat($bulk->montoNeto);
									$bulkList['bulkId'] = $bulk->acidlote;
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

				if (isset($response->tokenOTP->authToken)) {
					$this->session->set_flashdata('authToken', $response->tokenOTP->authToken);
				}

				$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
				$this->session->set_flashdata('bulkNotBillable', $bulkNotBillable);
			break;
			case -1:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
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
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data['btn1']['link'] = 'lotes-autorizacion';
			break;
			case -154:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('CONF_BULK_AUTH_MSG_SERV') == 'ON' ? $response->msg : lang('BULK_DAILY_AMOUNT_EXCEEDED');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -250:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('CONF_BULK_AUTH_MSG_SERV') == 'ON' ? $response->msg : lang('BULK_AMOUNT_EXCEEDED');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -439:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('BULK_WITHOUT_AUTH_PENDING');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -440:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('FILE_NOT_EXIST_ICBS');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -441:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('AUTH_ALREADY_PERFORMED_BY_USER');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -442:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('BULK_EXPIRED_TIME');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -458:
				$this->response->title = lang('BULK_AUTH_TITLE');
				$this->response->msg = lang('CONF_BULK_AUTH_MSG_SERV') == 'ON' ? $response->msg : lang('BULK_MONTHLY_AMOUNT_EXCEEDED');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
		}

		return $this->responseToTheView('callWs_AuthorizeBulk');
	}
	/**
	 * @info Genera orden de servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date January 05th, 2019
	 */
	public function callWs_ServiceOrder_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: ServiceOrder Method Initialized');

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
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoOrdenServicioMO';
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->lista = $listTemp;
		$this->dataRequest->lotesNF = $listTempNoBill;
		$this->dataRequest->tokenOTP = [
			'authToken' => $this->session->flashdata('authToken'),
			'tokenCliente' => isset($dataRequest->otpCode) ? $dataRequest->otpCode : ''
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'codigoGrupo' => $this->session->enterpriseInf->enterpriseGroup
		];

		$response = $this->sendToService('callWs_ServiceOrder');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = 'consulta-orden-de-servicio';
				$serviceOrdersList = [];

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
							case 'estatus':
								$serviceOrders['OrderStatus'] = $value;
								$serviceOrders['OrderVoidable'] = FALSE;
								if($value == '0') {
									$serviceOrders['OrderVoidable'] = $list->nofactura != '' && $list->fechafactura != '' ?: TRUE;
								}
								break;
							case 'montoComision':
								$serviceOrders['OrderCommission'] = currencyFormat($value);
								break;
							case 'montoIVA':
								$serviceOrders['OrderTax'] = currencyFormat($value);
								break;
							case 'montoOS':
								$serviceOrders['OrderAmount'] = currencyFormat($value);
								break;
							case 'montoDeposito':
								$serviceOrders['OrderDeposit'] = currencyFormat($value);
								break;
							case 'lotes':
								$serviceOrders['bulk'] = [];
								foreach($value AS $bulk) {
									$bulkList['bulkNumber'] = $bulk->acnumlote;
									$bulkList['bulkLoadDate'] = $bulk->dtfechorcarga;
									$bulkList['bulkLoadType'] = ucfirst(mb_strtolower($bulk->acnombre));
									$bulkList['bulkRecords'] = $bulk->ncantregs;
									$bulkList['bulkStatus'] = ucfirst(mb_strtolower($bulk->status));
									$bulkList['bulkAmount'] = currencyFormat($bulk->montoRecarga);
									$bulkList['bulkCommisAmount'] = currencyFormat($bulk->montoComision);
									$bulkList['bulkTotalAmount'] = currencyFormat($bulk->montoNeto);
									$bulkList['bulkId'] = $bulk->acidlote;
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
			case -56:
				$this->response->title = lang('BULK_SO_CREATE_TITLE');
				$this->response->msg = lang('BULK_SO_CREATE_FAILED');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -286:
				$this->response->title = lang('BULK_SO_CREATE_TITLE');
				$this->response->msg = lang('GEN_SO_CREATE_INCORRECT');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -287:
			case -288:
				$this->response->title = lang('BULK_SO_CREATE_TITLE');
				$this->response->msg = lang('GEN_SO_CREATE_EXPIRED');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['link'] = 'lotes-autorizacion';
				$this->response->data['btn1']['action'] = 'redirect';
			break;
		}

		$serviceOrdersList = $this->session->flashdata('serviceOrdersList');
		$bulkNotBillable = $this->session->flashdata('bulkNotBillable');
		$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
		$this->session->set_flashdata('bulkNotBillable', $bulkNotBillable);

		if ($this->session->flashdata('authToken') != NULL) {
			$authToken = $this->session->flashdata('authToken');
			$this->session->set_flashdata('authToken', $authToken);
		}

		return $this->responseToTheView('callWs_ServiceOrder');
	}
	/**
	 * @info Cancela calculo de orden de servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date January 05th, 2019
	 */
	public function callWs_CancelServiceOrder_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: cancelServiceOrder Method Initialized');

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
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoOrdenServicioMO';
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
		$noSeeDetail = ['Z', 'Y'];
		$allBulkSign = 'no-select-checkbox';
		$allBulkAuth = 'no-select-checkbox';

		if(lang('CONF_BULK_SELECT_ALL_SIGN') == 'ON') {
			$allBulkSign = 'toggle-all';
		}

		if(lang('CONF_BULK_SELECT_ALL_AUTH') == 'ON') {
			$allBulkAuth = 'toggle-all';
		}

		$sign = TRUE;
		$auth = TRUE;
		$order = isset($bulkList->usuario->orden) ? (int) $bulkList->usuario->orden : '';

		if($order == 1) {
			$auth = FALSE;
		}

		if($order == 2) {
			$sign = FALSE;
		}

		if(isset($bulkList->listaPorFirmar) && !empty($bulkList->listaPorFirmar)) {
			foreach($bulkList->listaPorFirmar AS $bulk) {
				$detailBulk['idBulk'] = $bulk->acidlote;
				$detailBulk['bulkNumber'] = $bulk->acnumlote;
				$detailBulk['loadDate'] = $bulk->dtfechorcarga;
				$detailBulk['idType'] = $bulk->ctipolote;
				$detailBulk['type'] = ucwords(mb_strtolower(substr($bulk->acnombre, 0, 20)));
				$detailBulk['records'] = $bulk->ncantregs;
				$detailBulk['amount'] = currencyFormat($bulk->nmonto);
				$detailBulk['selectBulk'] = $sign ? '' : 'no-select-checkbox';
				$signBulk[] = (object) $detailBulk;
			}
		}

		if(isset($bulkList->listaPorAutorizar) && !empty($bulkList->listaPorAutorizar)) {

			foreach($bulkList->listaPorAutorizar AS $bulk) {
				$detailBulk['idBulk'] = $bulk->acidlote;
				$detailBulk['bulkNumber'] = $bulk->acnumlote;
				$detailBulk['loadDate'] = $bulk->dtfechorcarga;
				$detailBulk['idType'] = $bulk->ctipolote;
				$detailBulk['type'] = ucwords(mb_strtolower(substr($bulk->acnombre, 0, 20)));
				$detailBulk['records'] = $bulk->ncantregs;
				$detailBulk['amount'] = currencyFormat($bulk->nmonto);
				$detailBulk['selectBulk'] = $auth ? '' : 'no-select-checkbox';
				$listAth = $bulk->accodusuarioa;
				$listAth = explode(',', $listAth);
				$detailBulk['selectRow'] = in_array($this->userName, $listAth) ? 'no-select-checkbox' : '';
				$detailBulk['selectRowContent'] = in_array($this->userName, $listAth) ? 'TRUE' : '';
				$detailBulk['seeDetail'] = in_array($bulk->ctipolote, $noSeeDetail) ? FALSE : TRUE;
				$authorizeBulk[] = (object) $detailBulk;
			}
		}

		$authorizeAttr = (object) [
			'toPAy' => $bulkList->ordenXPagar ?? 'N',
			'allBulkSign' => $allBulkSign,
			'allBulkAuth' => $allBulkAuth,
			'sign' => $sign,
			'auth' => $auth
		];
		$response = new stdClass();
		$response->signBulk = (object) $signBulk;
		$response->authorizeBulk = (object) $authorizeBulk;
		$response->authorizeAttr = $authorizeAttr;

		return $response;
	}
	/**
	 * @info Método para la solicutd de cuentas innominadas
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 06th, 2020
	 */
	public function callWs_UnnamedRequest_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: UnnamedRequest Method Initialized');

		$this->dataAccessLog->modulo = 'lotes';
		$this->dataAccessLog->function = 'Innominadas';
		$this->dataAccessLog->operation = 'Solictud de cuentas innominadas';

		$expiredDate = explode('/', $dataRequest->expiredDate);
		$expiredDate = $expiredDate[0].substr($expiredDate[1], -2);
		$password = '';

		if (isset($dataRequest->password)) {
			$password = $this->cryptography->decryptOnlyOneData($dataRequest->password);
		}

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = md5($password);
		}

		$startingLine1 = isset($dataRequest->startingLine1) ?
			implode(' ',array_filter(explode(' ', ucfirst(mb_strtolower($dataRequest->startingLine1))))) : '';
		$startingLine2 = isset($dataRequest->startingLine2) ?
			implode(' ',array_filter(explode(' ', ucfirst(mb_strtolower($dataRequest->startingLine2))))) : '';
		$this->dataRequest->idOperation = 'createCuentasInnominadas';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoSucursalesMO';
		$this->dataRequest->lotesTO = [
			'usuario' => $this->userName,
			'idEmpresa' => $this->session->enterpriseInf->idFiscal,
			'codCia' => $this->session->enterpriseInf->enterpriseCode,
			'codGrupo' => $this->session->enterpriseInf->enterpriseGroup,
			'codProducto' => $this->session->productInf->productPrefix,
			'fechaExp' => $expiredDate,
			'cantRegistros' => $dataRequest->maxCards,
			'lineaEmbozo1' => $startingLine1,
			'lineaEmbozo2' => $startingLine2,
			'sucursalCod' => isset($dataRequest->branchOffice) ? $dataRequest->branchOffice : '',
			'password' => $password,
			'monto' => '0',
			'idTipoLote' => "3",
			'formato' => "00",
			'tipoLote' => "INNOMINADAS",
			'fechaValor' => date('d/m/Y h:i:s'),
			'accanal' => "WEB",
			'reproceso' => true,
			"ubicacion" => "EM",
			"destinoEmb" => "01"
		];

		$response = $this->sendToService('callWs_UnnamedRequest');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->title = lang('BULK_UNNA_ACCOUNT');
				$this->response->msg = lang('BULK_UNNA_PROCESS_OK');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data['btn1']['link'] = 'lotes-autorizacion';
				break;
			case -1:
				$this->response->title = lang('BULK_UNNA_ACCOUNT');
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
				break;
			case -142:
				$this->response->title = lang('BULK_UNNA_ACCOUNT');
				$this->response->msg = lang('BULK_NO_LOAD');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->responseTotheView('callWs_UnnamedRequest');
	}
	/**
	 * @info Método para la afiliación de cuentas innominadas
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 06th, 2020
	 */
	public function callWs_UnnamedAffiliate_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: unnamedAffiliate Method Initialized');

		$this->dataAccessLog->modulo = 'lotes';
		$this->dataAccessLog->function = 'Innominadas';
		$this->dataAccessLog->operation = 'Afiliación de cuentas innominadas';

		$initialDate = $dataRequest->initialDate != '' ? convertDate($dataRequest->initialDate) : '';
		$finalDate = $dataRequest->finalDate != '' ? convertDate($dataRequest->finalDate) : '';

		$this->dataRequest->idOperation = 'getListadoLotes';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoLotesMO';
		$this->dataRequest->dtfechorcargaIni = $initialDate;
		$this->dataRequest->dtfechorcargaFin = $finalDate;
		$this->dataRequest->nombreEmpresa = '';
		$this->dataRequest->acdir = '';
		$this->dataRequest->rif = '';
		$this->dataRequest->lista = [
			[
				"exonerado"=>'',
				'ctipolote' => '3',
				'cestatus' => '4',
				'acprefix' => $this->session->productInf->productPrefix,
				"rifEmpresa"=> $this->session->enterpriseInf->idFiscal,
				"acnumlote"=> $dataRequest->bulkNumber,
			]
		];

		$response = $this->sendToService('callWs_unnamedAffiliate');

		$detailInfo = [
			'bulkHeader' => [
				lang('GEN_TABLE_BULK_NUMBER'),
				lang('GEN_TABLE_NUMBER_CARDS'),
				lang('GEN_TABLE_BULK_ISSUE_DATE'),
				lang('GEN_TABLE_STATUS'),
				lang('GEN_TABLE_OPTIONS')
			],
			'bulkRecords' => [],
		];

		switch ($this->isResponseRc) {
			case 0:
				$unnamedList = $response->bean;
				$this->response->code = 0;

				if(isset($unnamedList->lista) && count($unnamedList->lista) > 0) {
					foreach ($unnamedList->lista AS $records) {
						$record = new stdClass();
						$record->bulkId = $records->acidlote;
						$record->bulkNumber = $records->acnumlote;
						$record->totalCards = $records->ncantregs;
						$record->issuanDate = $records->dtfechorcarga;
						$record->amount = $records->nmonto;
						$record->status = ucfirst(mb_strtolower($records->status));
						array_push(
							$detailInfo['bulkRecords'],
							$record
						);
					}
				}
			break;
		}

		$this->response->data->bulkInfo = (object) $detailInfo;

		return $this->responseToTheView('callWs_unnamedAffiliate');
	}
	/**
	 * @info Método para ver el detalle de un lote innominado
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 09th, 2020
	 */
	public function callWs_UnnmamedDetail_Bulk($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: unnmamedDetail Method Initialized');

		$this->dataAccessLog->modulo = 'lotes';
		$this->dataAccessLog->function = 'Innominadas';
		$this->dataAccessLog->operation = 'Detalle de lote';

		$this->dataRequest->idOperation = 'getListadoTarjetasInnominadas';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoLotesMO';
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;
		$this->dataRequest->tarjetasInnominadas = [
			[
				'numLote' => $dataRequest->bulkNumber,
				'idExtEmp' => $this->session->enterpriseInf->idFiscal,
				'estatus' => '0',
				'enmascarar' => TRUE
			]
		];

		$response = $this->sendToService('callWs_unnmamedDetail');

		$detailInfo = [
			'fiscalId' => $this->session->enterpriseInf->idFiscal,
			'enterpriseName' => $this->session->enterpriseInf->enterpriseName,
			'issuanDate' => $dataRequest->issuanDate,
			'bulkNumber' => $dataRequest->bulkNumber,
			'ammount' => $dataRequest->amount,
			'totalRecords' => $dataRequest->totalCards,
			'bulkHeader' => [
				lang('GEN_TABLE_CARD_NUMBER'),
				lang('GEN_TABLE_ACCOUNT_NUMBER'),
				lang('GEN_TABLE_DNI'),
				lang('GEN_TABLE_CARDHOLDER'),
				lang('GEN_TABLE_STATUS'),
			],
			'bulkRecords' => [],
		];

		if (lang('CONF_UNNA_ACCOUNT_NUMBER') == 'OFF') {
			unset($detailInfo['bulkHeader'][1]);
		}

		switch ($this->isResponseRc) {
			case 0:
				$unnamedDetail = $response->bean;
				$this->response->code = 0;

				if(isset($unnamedDetail->tarjetasInnominadas) && count($unnamedDetail->tarjetasInnominadas) > 0) {
					foreach ($unnamedDetail->tarjetasInnominadas AS $records) {
						$record = new stdClass();
						$record->cardNumber = $records->nroTarjeta;

						if (lang('CONF_UNNA_ACCOUNT_NUMBER') == 'ON') {
							$record->accountNumber = maskString($records->nroCuenta, 6, 4);
						}

						$record->idDoc = $records->idExtPer;
						$record->cardHolder = $records->nombre;
						$record->cardHoldLastName = $records->apellido;
						$record->status = $records->estatus == '0' ? 'No afiliado' : 'Afiliado';
						$record->cardHolder = $record->cardHolder.' '.$record->cardHoldLastName;
						unset($record->cardHoldLastName);
						array_push(
							$detailInfo['bulkRecords'],
							$record
						);
					}
				}
			break;
			case -150:
				$this->response->title = 'Cuentas Innominadas';
				$this->response->msg = lang('BULK_UNNA_REQ_NONCARDS');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data->resp['btn1']['link'] = lang('GEN_LINK_BULK_UNNAMED_AFFIL');
			break;
		}

		$this->response->data->bulkInfo = (object) $detailInfo;

		return $this->responseToTheView('callWs_unnamedAffiliate');
	}
}
