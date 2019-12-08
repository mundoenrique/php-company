<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los lotes
 * @author J. Enrique Peñaloza Piñero
 * @date December 8th, 2019
 */
class Novo_Lots_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Lots Model Class Initialized');
	}
	/**
	 * @info Método para obtener los tipos de lte asociados a un programa
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 8th, 2019
	 */
	public function callWs_getPendingLots_Lots()
	{
		log_message('INFO', 'NOVO Lots Model: getPendingLots method Initialized');

		$this->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar Lotes';
		$this->dataAccessLog->operation = 'Lotes por confirmar';

		$this->dataRequest->idOperation = 'buscarLotesPorConfirmar';
		$this->dataRequest->lotesTO = [
			'idEmpresa' => $this->session->enterpriseInf->idFiscal,
			'codProducto' => $this->session->productPrefix
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName
		];

		$response = $this->sendToService(lang('GEN_GET_PEN_LOTS'));
		$pendingLotsList = [];

		switch($this->isResponseRc) {
			case 0:
				//log_message('info', 'novo lotes pendientes--------'.json_encode($response));
				$this->response->code = 0;

				foreach($response->lista AS $pos => $pendingLots) {
					//log_message('info', 'novo lotes pos '.$pos.' lot'.json_encode($lot));
					$lots['lotNum'] = $response->lista[$pos]->numLote != '' ? $response->lista[$pos]->numLote : '---';
					$lots['status'] = $response->lista[$pos]->estatus;
					$lots['fileName'] = $response->lista[$pos]->nombreArchivo;
					$lots['ticketId'] = $response->lista[$pos]->idTicket;
					$lots['loadDate'] = $response->lista[$pos]->fechaCarga;
					$pendinglots[] = $lots;
				}
				//log_message('info', 'novo lotes pendientes--------'.json_encode($pendinglots));
				break;
		}

		if($this->isResponseRc != 0) {

		}

		return $this->responseToTheView(lang('GEN_GET_PEN_LOTS'));
	}
	/**
	 * @info Método para obtener los tipos de lte asociados a un programa
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 8th, 2019
	 */
	public function callWs_getTypeLots_Lots()
	{
		log_message('INFO', 'NOVO Lots Model: getTypeLots method Initialized');

		$this->className = 'com.novo.objects.MO.ConfirmarLoteMO';
		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Cargar Lotes';
		$this->dataAccessLog->operation = 'Obtener lotes pendientes';

		$this->dataRequest->idOperation = 'consultarTipoLote';
		$this->dataRequest->lotesTO = [
			'codProducto' => $this->session->productPrefix
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName
		];

		$response = $this->sendToService(lang('GEN_GET_TYPE_LOT'));
		$typesLots = [
			[
				'format' => '',
				'key' => '',
				'text' => 'Selecciona'
			]
		];

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				foreach($response->lista AS $pos) {
					foreach($pos AS $items => $value) {
						switch ($items) {
							case 'idTipoLote':
								$types['key'] = $value;
								break;
							case 'formato':
								$types['format'] = $value;
								break;
							case 'tipoLote':
								$types['text'] = $value;
								break;
						}
					}
					$typesLots[] = $types;
				}
				$this->response->data->typesLots = $typesLots[0];
				break;
		}
		if($this->isResponseRc != 0) {
			$typesLots = [
				[
					'format' => '',
					'key' => '',
					'text' => 'Intenta de nuevo'
				]
			];
		}

		return $this->responseToTheView(lang('GEN_GET_TYPE_LOT'));
	}
}
