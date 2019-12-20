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
						case '1':
							$bulk['statusPr'] = 'status-pr ';
							$bulk['statusColor'] = ' bg-vista-blue';
							$bulk['statusText'] = 'Válido';
							break;
						case '5':
							$bulk['statusPr'] = '';
							$bulk['statusColor'] = ' bg-pink-salmon';
							$bulk['statusText'] = 'Con errores';
							break;
						case '6':
							$bulk['statusPr'] = 'status-pr ';
							$bulk['statusColor'] = ' bg-trikemaster';
							$bulk['statusText'] = 'Válido';
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

				if($select) {
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
					$type['key'] = ucfirst(mb_strtolower($response->lista[$pos]->idTipoLote));
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
				'formatoLote' => $dataRequest->formatBulk,
			];
			$this->dataRequest->usuario = [
				'userName' => $this->userName
			];

			$response = $this->sendToService(lang('GEN_LOAD_BULK'));
			$respLoadBulk = FALSE;

			switch ($this->isResponseRc) {
				case 0:
					$this->response->msg = lang('BULK_SUCCESS');
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
					$title = lang('BULK_NO_LOAD_TITLE');
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
				$this->response->title = isset($code) ? $title : lang('BULK_TITLE');
				if($this->isResponseRc != 0) {
					$this->response->data = [
						'btn1'=> [
							'action'=> 'close'
						]
					];
				}
			}
		} else {
			$this->response->code = 2;
			$this->response->title = lang('BULK_TITLE');
			$this->response->msg = lang('BULK_FILE_NO_MOVE');
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
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
				$this->response->code = 0;
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = lang('BULK_DELETE_SUCCESS');
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'link' => base_url('cargar-lotes'),
					'action' => 'redirect'
				];
				break;
			case -1:
				$this->response->code = 0;
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = lang('RESP_PASSWORD_NO_VALID');
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
				$this->response->data->detailBulk = (object) $detailBulk;

				break;
		}

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
				$this->response->msg = lang('BULK_CONFIRM_SUCCESS');
				$this->response->data['btn1']['link'] = base_url('lotes-autorizacion');
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
				break;
		}

		if($this->isResponseRc != 0) {
			$this->session->set_flashdata('bulkConfirmInfo', $bulkConfirmInfo);
		}

		return $this->responseToTheView(lang('GEN_CONFIRM_BULK'));
	}
}
