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
	public function callWs_getPendingLots_Bulk()
	{
		log_message('INFO', 'NOVO Bulk Model: getPendingLots method Initialized');

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

		$response = $this->sendToService(lang('GEN_GET_PEN_LOTS'));
		$pendingLotsList = [];

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				foreach($response->lista AS $pos => $pendingLots) {
					$lots = [];
					$lots['lotNum'] = $response->lista[$pos]->numLote != '' ? $response->lista[$pos]->numLote : '---';
					$lotStatus = $response->lista[$pos]->estatus;
					switch ($lotStatus) {
						case '1':
							$lots['statusPr'] = 'status-pr ';
							$lots['statusColor'] = ' bg-vista-blue';
							$lots['statusText'] = 'Válido';
							break;
						case '5':
							$lots['statusPr'] = '';
							$lots['statusColor'] = ' bg-pink-salmon';
							$lots['statusText'] = 'Con errores';
							break;
						case '6':
							$lots['statusPr'] = 'status-pr ';
							$lots['statusColor'] = ' bg-trikemaster';
							$lots['statusText'] = 'Válido';
							break;
					}
					$lots['status'] = $lotStatus;
					$lots['fileName'] = $response->lista[$pos]->nombreArchivo;
					$lots['ticketId'] = $response->lista[$pos]->idTicket;
					$lots['loadDate'] = $response->lista[$pos]->fechaCarga;
					$pendingLotsList[] = (object) $lots;
				}
				break;
				case -15:
					$this->response->code = 0;
					break;
		}
		$this->response->data->pendinglots = (object) $pendingLotsList;

		return $this->responseToTheView(lang('GEN_GET_PEN_LOTS'));
	}
	/**
	 * @info Método para obtener los tipos de lte asociados a un programa
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 8th, 2019
	 */
	public function callWs_getTypeLots_Bulk()
	{
		log_message('INFO', 'NOVO Bulk Model: getTypeLots method Initialized');

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
					'text' => 'Selecciona un tipo de lote'
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
				'text' => 'Intenta de nuevo'
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
					$this->response->msg = "Lote cargado exitosamente";
					$this->response->icon = lang('GEN_ICON_SUCCESS');
					$this->response->data['btn1']['link'] = base_url('cargar-lotes');
					$respLoadBulk = TRUE;
					break;
				case -108:
					$this->response->msg = "No fue posible cargar el lote, por favor intentalo de nuevo";
					$respLoadBulk = TRUE;
					break;
					case -109:
					case -256:
					$this->response->msg = "No fue posible cargar el lote, por favor intentalo de nuevo";
					$respLoadBulk = TRUE;
					break;
					case -128:
					$code = 3;
					$title = 'El lote no se cargo:';
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
				$this->response->title = isset($code) ? $title : "Cargar lote";
				$this->response->data = [
					'btn1'=> [
						'action'=> 'close'
					]
				];
			}
		} else {
			$this->response->code = 3;
			$this->response->title = 'Cargar Lote';
			$this->response->msg = "No fue posible mover el archivo al servidor, por favor intentalo de nuevo";
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
		}

		return $this->responseToTheView(lang('GEN_LOAD_BULK'));
	}
}
