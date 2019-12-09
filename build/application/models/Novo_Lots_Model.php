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
			'codProducto' => $this->session->productInf->productPrefix
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
					$lots = [];
					$lots['lotNum'] = $response->lista[$pos]->numLote != '' ? $response->lista[$pos]->numLote : '---';
					$lots['status'] = $response->lista[$pos]->estatus;
					$lots['fileName'] = $response->lista[$pos]->nombreArchivo;
					$lots['ticketId'] = $response->lista[$pos]->idTicket;
					$lots['loadDate'] = $response->lista[$pos]->fechaCarga;
					$pendinglots[] = (object) $lots;
				}
				//log_message('info', 'novo lotes pendientes--------'.json_encode($pendinglots));
				break;
		}
		$this->response->data->pendinglots = (object) $pendinglots;
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
		$this->dataAccessLog->operation = 'Obtener tipos lote';

		$this->dataRequest->idOperation = 'consultarTipoLote';
		$this->dataRequest->lotesTO = [
			'codProducto' => $this->session->productInf->productPrefix
		];
		$this->dataRequest->usuario = [
			'userName' => $this->userName
		];

		$response = $this->sendToService(lang('GEN_GET_TYPE_LOT'));
		$typesLot[] = (object) [
			'key' => '',
			'format' => '',
			'text' => 'Selecciona un tipo de lote'
		];

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				foreach($response->lista AS $pos => $types) {
					$type = [];
					$type['key'] = ucfirst(mb_strtolower($response->lista[$pos]->idTipoLote));
					$type['format'] = ucfirst(mb_strtolower($response->lista[$pos]->formato));
					$type['text'] = ucfirst(mb_strtolower($response->lista[$pos]->tipoLote));
					$typesLot[] = (object) $type;
				}
				$this->response->data->typesLot = (object) $typesLot;
				break;
		}
		if($this->isResponseRc != 0) {
			$typesLot[] = (object) [
				'format' => '',
				'key' => '',
				'text' => 'Intenta de nuevo'
			];
		}

		return $this->responseToTheView(lang('GEN_GET_TYPE_LOT'));
	}
}
