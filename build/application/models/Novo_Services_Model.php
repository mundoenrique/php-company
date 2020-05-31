<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los lotes
 * @author
 *
 */
class Novo_Services_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Services Model Class Initialized');
	}
	/**
	 * @info Método para
	 * @author
	 */
	public function callWs_TransfMasterAccount_Services($dataRequest)
	{
		log_message('INFO', 'NOVO Services Model: TransfMasterAccount Method Initialized');

		$this->className = 'com.novo.objects.MO.TransferenciaMO';

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Transferencia maestra';
		$this->dataAccessLog->operation = 'Obtener lista de tarjetas';

		$this->dataRequest->idOperation = 'buscarTransferenciaM';
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;
		$this->dataRequest->listaTarjetas = [
			[
				'paginaActual' => (int) ($dataRequest->start / 10) + 1,
				'tamanoPagina' => (int) $dataRequest->length,
				'paginar' => TRUE
			]
		];
		$this->dataRequest->usuario = [
			'userName' => $this->session->userName
		];
		$this->dataRequest->listadoTarjetas = [
			'lista' => [
				[
					'noTarjeta' => $dataRequest->cardNumber,
					'id_ext_per' => $dataRequest->idNumber
				]
			]
		];

		$response = $this->sendToService('callWs_TransfMasterAccount');
		$cardsList = [];
		switch($this->isResponseRc) {
			case 0:
				foreach ($response->listadoTarjetas->lista AS $cards) {
					$record = new stdClass();
					$record->cardNumber = $cards->noTarjetaConMascara;
					$record->name = $cards->NombreCliente;
					$record->idNumber = $cards->id_ext_per;
					$record->status = isset($cards->codBloqueo) ? mb_strtolower($cards->codBloqueo) : '';
					$record->amount = '0'.lang('GEN_DECIMAL').'00';
					array_push(
						$cardsList,
						$record
					);
				}

				$this->response->code = 0;
				$this->response->params = $response->maestroParametros;
				$this->response->balance = $response->maestroDeposito->saldoDisponible;
				$this->response->access = [
					'TRASAL' => $this->verify_access->verifyAuthorization('TRAMAE', 'TRASAL'),
					'TRACAR' => $this->verify_access->verifyAuthorization('TRAMAE', 'TRACAR'),
					'TRAABO' => $this->verify_access->verifyAuthorization('TRAMAE', 'TRAABO'),
					'TRABLQ' => $this->verify_access->verifyAuthorization('TRAMAE', 'TRABLQ'),
					'TRAASG' => $this->verify_access->verifyAuthorization('TRAMAE', 'TRAASG'),
					'TRADBL' => $this->verify_access->verifyAuthorization('TRAMAE', 'TRADBL'),
				];
				$this->response->draw = (int) $dataRequest->draw;
				$this->response->recordsTotal = (int) $response->listaTarjetas[0]->totalRegistros;
				$this->response->recordsFiltered = (int) $response->listaTarjetas[0]->totalRegistros;
				$this->response->data = $cardsList;
				break;
			case -5000:
				$this->response->code = 1;

				break;
			case -6000:
				$this->response->code = 2;

				break;
			case -7000:
				$this->response->code = 3;
				$this->response->msg = lang('REEMPLAZAR POR TRADUCCION DESDE RESPONSE-LANG');
				$this->response->icon = 'ui-icon-info';
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('BUTTON-ACCEPT'),
						'link'=> 'inicio',
						'action'=> 'redirect'
					],
					'btn2'=> [
						'text'=> lang('BUTTON-CANCEL'),
						'link'=> FALSE,
						'action'=> 'close'
					]
				];
			break;

		}

		return $this->responseToTheView('callWs_TransfMasterAccount');
	}
	/**
	 * @info Método para
	 * @author
	 */
	public function callWs_ActionMasterAccount_Services($dataRequest)
	{
		log_message('INFO', 'NOVO Services Model: ActionMasterAccount Method Initialized');

		$this->className = 'com.novo.objects.MO.TransferenciaMO';

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Transferencia maestra';

		$cardsList = [];

		foreach($dataRequest->cards AS $cardsInfo) {
			$cardsInfo = json_decode($cardsInfo);
			$card  = [
				'noTarjeta' => $cardsInfo->Cardnumber,
				'id_ext_per' => $cardsInfo->idNumber,
				'montoTransaccion' => $cardsInfo->amount
			];

			switch ($dataRequest->action) {
				case lang('GEN_CHECK_BALANCE'):
				case 'consulta':
				case 'Bloqueo temporal':
				case 'Desbloqueo tarjeta':
					unset($card['montoTransaccion']);
				break;
				case 'Asignación tarjeta':
					unset($card['montoTransaccion']);
					$card['noTarjetaAsig'] = $cardsInfo->cardNumberAs;
				break;
			}

			$cardsList[] = $card;
		}

		switch ($dataRequest->action) {
			case lang('GEN_CHECK_BALANCE'):
			case 'consulta':
				$this->dataAccessLog->operation = lang('GEN_CHECK_BALANCE');
				$this->dataRequest->idOperation = 'saldoTM';
			break;
			case lang('GEN_CREDIT_TO_CARD'):
			case 'abono':
				$this->dataAccessLog->operation = lang('GEN_CREDIT_TO_CARD');
				$this->dataRequest->idOperation = 'saldoTM';
			break;
			case lang('GEN_DEBIT_TO_CARD'):
			case 'cargo':
				$this->dataAccessLog->operation = lang('GEN_DEBIT_TO_CARD');
				$this->dataRequest->idOperation = 'cargoTM';
			break;
			case 'Bloqueo temporal':
				$this->dataAccessLog->operation = 'Bloqueo temporal';
				$this->dataRequest->idOperation = 'bloqueoTM';
			break;
			case 'Desbloqueo tarjeta':
				$this->dataAccessLog->operation = 'Desbloqueo tarjeta';
				$this->dataRequest->idOperation = 'desbloqueoTM';
			break;
			case 'Asignación tarjeta':
				$this->dataAccessLog->operation = 'Reasignar tarjeta';
				$this->dataRequest->idOperation = 'reasignacionTM';
			break;
		}

		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;
		$this->dataRequest->listaTarjetas = [
			[
				'paginaActual' => 1,
				'tamanoPagina' => 1,
				'paginar' => FALSE
			]
		];
		$this->dataRequest->listadoTarjetas = [
			'lista' => $cardsList
		];
		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->password)
		);
		$this->dataRequest->usuario = [
			'userName' => $this->session->userName,
			'password' => md5($password)
		];

		$response = $this->sendToService('callWs_ActionMasterAccount');

		$this->response->msg = $response->msg;
		$this->response->data['btn1']['text'] = lang('GEN_BTN_ACCEPT');
		$this->response->data['btn1']['action'] = 'close';

		switch ($this->isResponseRc) {
			case 0:
				# code...
			break;
			case -33:
			case -266:
				log_message('info', '*******************   '.$response->bean);
			break;

			default:
				# code...
			break;
		}

		return $this->responseToTheView('callWs_ActionMasterAccount');
	}
}
