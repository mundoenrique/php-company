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
		$this->response->params['costoComisionTrans'] = '--';
		$this->response->params['costoComisionCons'] = '--';
		$this->response->balance = '--';
		$this->response->recordsTotal = 0;
		$this->response->recordsFiltered = 0;
		$this->response->access = [
			'TRASAL' => FALSE,
			'TRACAR' => FALSE,
			'TRAABO' => FALSE,
			'TRABLQ' => FALSE,
			'TRAASG' => FALSE,
			'TRADBL' => FALSE,
		];

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
			break;
			case -150:
				$this->response->code = 1;
				$this->response->title = lang('GEN_MENU_SERV_MASTER_ACCOUNT');
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->msg = 'No se encontraron resultados para tu busqueda';
				$this->response->data['btn1']['action'] = 'close';
			break;
		}

		$this->response->draw = (int) $dataRequest->draw;
		$this->response->dataResp = $this->response->data;
		$this->response->data = $cardsList;

		return $this->responseToTheView('callWs_TransfMasterAccount');
	}
	/**
	 * @info Método para realizar acciones de cuenta maestra
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 29th, 2020
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
				case 'Consulta':
				case lang('GEN_TEMPORARY_LOCK'):
				case lang('GEN_UNLOCK_CARD'):
					unset($card['montoTransaccion']);
				break;
				case lang('GEN_CARD_ASSIGNMENT'):
					unset($card['montoTransaccion']);
					$card['noTarjetaAsig'] = $cardsInfo->cardNumberAs;
				break;
			}

			$cardsList[] = $card;
		}

		switch ($dataRequest->action) {
			case lang('GEN_CHECK_BALANCE'):
			case 'Consulta':
				$this->dataAccessLog->operation = lang('GEN_CHECK_BALANCE');
				$this->dataRequest->idOperation = 'saldoTM';
			break;
			case lang('GEN_CREDIT_TO_CARD'):
			case 'Abono':
				$this->dataAccessLog->operation = lang('GEN_CREDIT_TO_CARD');
				$this->dataRequest->idOperation = 'abonarTM';
			break;
			case lang('GEN_DEBIT_TO_CARD'):
			case 'Cargo':
				$this->dataAccessLog->operation = lang('GEN_DEBIT_TO_CARD');
				$this->dataRequest->idOperation = 'cargoTM';
			break;
			case lang('GEN_TEMPORARY_LOCK'):
				$this->dataAccessLog->operation = lang('GEN_TEMPORARY_LOCK');
				$this->dataRequest->idOperation = 'bloqueoTM';
			break;
			case lang('GEN_UNLOCK_CARD'):
				$this->dataAccessLog->operation = lang('GEN_UNLOCK_CARD');
				$this->dataRequest->idOperation = 'desbloqueoTM';
			break;
			case lang('GEN_CARD_ASSIGNMENT'):
				$this->dataAccessLog->operation = lang('GEN_CARD_ASSIGNMENT');
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

		if (lang('CONF_HASH_PASS') == 'ON' || $this->session->autoLogin == 'false') {
			$password = md5($password);
		}

		$this->dataRequest->usuario = [
			'userName' => $this->session->userName,
			'password' => $password
		];

		$response = $this->sendToService('callWs_ActionMasterAccount');
		$listResopnse = [];
		$listFail = [];

		switch ($this->isResponseRc) {
			case 0:
				$this->response->title = $dataRequest->action;
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data['btn1']['action'] = 'close';

				if ($dataRequest->action == lang('GEN_TEMPORARY_LOCK') || $dataRequest->action == lang('GEN_UNLOCK_CARD')) {
					$blockType = $dataRequest->action == lang('GEN_TEMPORARY_LOCK') ? 'Bloqueda' : 'desbloqueda';
					$this->response->msg =  novoLang('La tarjeta %s ha sido %s.', [$cardsList[0]['noTarjeta'], $blockType]);
					$this->response->update = TRUE;
				}

				if ($dataRequest->action == lang('GEN_CARD_ASSIGNMENT')) {
					$this->response->msg =  novoLang('La tarjeta %s ha sido reemplazada por %s.', [$cardsList[0]['noTarjeta'], $cardsList[0]['noTarjetaAsig']]);
				}

				if ($dataRequest->action == lang('GEN_CHECK_BALANCE') || $dataRequest->action == 'Consulta') {
					$this->response->code = 0;
					foreach ($response->listadoTarjetas->lista as $key => $cards) {
						$record = new stdClass();
						$record->usersId = $cards->id_ext_per;
						$record->cardNumber = $cards->noTarjetaConMascara;
						$record->balance = isset($cards->saldos) ?  lang('GEN_CURRENCY').' '.$cards->saldos->disponible : '--';
						$listResopnse[] = $record;

						if ($record->balance == '--') {
							$this->response->code = 4;
							$listFail[] = $cards->noTarjetaConMascara;
						}
					}

					if (count($listFail) > 0) {
						$this->response->code = 2;
						$this->response->msg = 'No fue posible obtener el saldo para';
					}
				}

				if ($dataRequest->action == lang('GEN_CREDIT_TO_CARD') || $dataRequest->action == 'Abono' || $dataRequest->action == lang('GEN_DEBIT_TO_CARD') || $dataRequest->action == 'Cargo') {
					foreach ($response->listadoTarjetas->lista as $key => $cards) {
						$record = new stdClass();
						$record->usersId = $cards->id_ext_per;
						$record->cardNumber = $cards->noTarjetaConMascara;
						$record->amount = isset($cards->montoTransaccion) ?  lang('GEN_CURRENCY').' '.$cards->montoTransaccion : '--';
						$listResopnse[] = $record;
					}

					$this->response->code = 2;
					$this->response->msg = 'Datos de la transacción';
				}

				$this->response->data['listResponse'] = $listResopnse;
				$this->response->data['listFail'] = $listFail;

				if (isset($response->maestroDeposito)) {
					$this->response->data['balance'] = lang('GEN_CURRENCY').' '.$response->maestroDeposito->saldoDisponible;
				}
			break;
			case -1:
				$this->response->title = $dataRequest->action;
				$this->response->msg = lang('RESP_PASSWORD_NO_VALID');
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -21:
			case -22:
				$this->response->title = $dataRequest->action;
				$this->response->msg = 'No fue posible realizar la trasacción, intentalo de nuevo';
				$this->response->icon = lang('GEN_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -33:
			case -100:
				$this->response->title = $dataRequest->action;
				$this->response->msg = 'El saldo no esta disponible';
				$this->response->icon = lang('GEN_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -152:
				$this->response->title = $dataRequest->action;
				$this->response->msg = 'La transacción no supera el monto mínimo por operación';
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -153:
				$this->response->title = $dataRequest->action;
				$this->response->msg = 'Alcanzaste el monto maximo de operaciones semenales';
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -154:
				$this->response->title = $dataRequest->action;
				$this->response->msg = 'Alcanzaste el monto maximo de operaciones diarias';
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -155:
				$this->response->title = $dataRequest->action;
				$this->response->msg = 'Tu saldo no es suficiente para realizar la transacción';
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -157:
				$this->response->title = $dataRequest->action;
				$this->response->msg = 'Alcanzaste el límite de operaciones diarias';
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -242:
				$this->response->title = $dataRequest->action;
				$this->response->msg = 'Alcanzaste el límite de transacciones';
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -267:
				$this->response->title = $dataRequest->action;
				$this->response->msg = novoLang('La tarjeta %s ya se encunetra bloqueda.', $cardsList[0]['noTarjeta']);
				$this->response->icon = lang('GEN_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
			break;
		}

		return $this->responseToTheView('callWs_ActionMasterAccount');
	}
	/**
	 * @info Método para obtener lista de tarjetas
	 * @author J. Enrique Peñaloza Piñero
	 * @date July 03rd, 2020
	 */
	public function callWs_CardsInquiry_Services($dataRequest)
	{
		log_message('INFO', 'Novo Services Model: CardsInquiry Method Initialized');

		$this->className = 'com.novo.objects.MO.ListadoEmisionesMO';

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Consulta de tarjetas';
		$this->dataAccessLog->operation = isset($dataRequest->action) ? 'Descargar archivo' : 'Obtener lista de tarjetas';

		$this->dataRequest->idOperation = isset($dataRequest->action) ? 'buscarTarjetasEmitidasExcel' : 'buscarTarjetasEmitidas';
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->accodcia = $this->session->enterpriseInf->enterpriseCode;
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;
		$this->dataRequest->usuario = [
			'userName' => $this->session->userName
		];
		$this->dataRequest->nrOrdenServicio = $dataRequest->orderNumber;
		$this->dataRequest->nroLote = $dataRequest->bulkNumber;
		$this->dataRequest->tipoDocumento = isset($dataRequest->docType) ? $dataRequest->docType : '';
		$this->dataRequest->cedula = $dataRequest->idNumberP;
		$this->dataRequest->nroTarjeta = $dataRequest->cardNumberP;
		$this->dataRequest->opcion = 'EMI_REC';
		$this->dataRequest->pagina = 0;

		$response = $this->sendToService('callWs_CardsInquiry');
		$cardsList = [];
		$operList = ['INQUIRY_BALANCE' => FALSE];
		$massiveOptions = [];

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				if (isset($dataRequest->action)) {
					$this->response->data['file'] = $response->archivo;
					$this->response->data['name'] = $response->nombre.'.xls';
					$this->response->data['ext'] = 'xls';
				} else {
					foreach ($response->detalleEmisiones AS $cards) {
						$record = new stdClass();
						$record->cardNumber = $cards->nroTarjeta;
						$record->orderNumber = $cards->ordenS;
						$record->bulkNumber = $cards->nroLote;
						$issueStatus = ucfirst(mb_strtolower($cards->edoEmision));

						if (strpos($cards->edoEmision, '/') !== FALSE) {
							$issueStatus = strstr($issueStatus, '/', TRUE);
						}

						$record->issueStatus = trim($issueStatus);
						$record->cardStatus = trim(ucfirst(mb_strtolower($cards->edoPlastico)));
						$record->name = ucwords(mb_strtolower($cards->nombre));
						$record->idNumber = substr($cards->cedula, -6) == substr($cards->nroTarjeta, -6) ? '' : $cards->cedula;
						$record->idNumberSend = $cards->cedula;
						$record->email = $cards->email;
						$record->celPhone = $cards->numCelular;
						$record->names = $cards->nombres;
						$record->lastName = $cards->apellidos;
						$options = [
							'NO_OPER' => '--'
						];

						foreach ($response->operacioneTarjeta AS $status) {
							if ($status->edoTarjeta == $cards->edoEmision) {
								foreach ($status->operacion AS $oper) {
									$key = mb_strtoupper(str_replace(' ', '_', $oper));
									$options[lang('SERVICES_INQUIRY_OPTIONS')[$key]] = lang('SERVICES_INQUIRY_OPTIONS')[$key];
									$massiveOptions[lang('SERVICES_INQUIRY_OPTIONS')[$key]] = lang('SERVICES_INQUIRY_'.lang('SERVICES_INQUIRY_OPTIONS')[$key]);
								}
								unset($options['NO_OPER']);
							}
						}

						$record->options = $options;
						array_push($cardsList, $record);

						if (array_key_exists('INQUIRY_BALANCE', $options)) {
							$operList['INQUIRY_BALANCE'] =  TRUE;
						}

						if (array_key_exists('UPDATE_DATA', $massiveOptions)) {
							unset($massiveOptions['UPDATE_DATA']);
						}
					}
				}
			break;
			case -150:
				$this->response->code = 1;
			break;
			default:
				if (isset($dataRequest->action) && $this->isResponseRc != -29 && $this->isResponseRc != -61) {
					$this->response->title = lang('GEN_DOWNLOAD_FILE');
					$this->response->icon =  lang('GEN_ICON_WARNING');
					$this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
					$this->response->data['btn1']['action'] = 'close';
				}
		}

		$this->response->data['cardsList'] = $cardsList;
		$this->response->data['operList'] = $operList;
		$this->response->data['massiveOptions'] = $massiveOptions;

		return $this->responseToTheView('callWs_CardsInquiry');
	}
	/**
	 * @info Método para realizar acciones de consulta de tarjetas
	 * @author J. Enrique Peñaloza Piñero
	 * @date July 06th, 2020
	 */
	public function callWs_InquiriesActions_Services($dataRequest)
	{
		log_message('INFO', 'NOVO Services Model: InquiriesActions Method Initialized');

		$this->className = 'com.novo.objects.MO.SeguimientoLoteMO';

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Consulta de tarjetas';
		$this->dataAccessLog->operation = lang('SERVICES_INQUIRY_'.$dataRequest->action);

		switch ($dataRequest->action) {
			case 'INQUIRY_BALANCE':
			case 'LOCK_CARD':
			case 'UNLOCK_CARD':
				$this->className = 'com.novo.business.lote.seguimiento.resources.NovoBusinessOperacionSeguimientoWS';
			break;
			case 'UPDATE_DATA':
			case 'DELIVER_TO_CARDHOLDER':
			case 'SEND_TO_ENTERPRISE':
			case 'RECEIVE_IN_ENTERPRISE':
			case 'RECEIVE_IN_BANK':
			break;
		}

		$dataList = [];

		foreach ($dataRequest->cards AS $list) {
			$list = json_decode($list);
			$data = [
				'idLote' => $list->bulkNumber,
				'edoNuevo' => lang('SERVICES_INQUIRY_'.$dataRequest->action),
				'edoAnterior' => $list->issueStatus,
				'numeroTarjeta' => $list->cardNumber,
				'idExtPer' => $list->idNumberSend,
				'idExtEmp' => $this->session->enterpriseInf->idFiscal,
				'accodcia' => $this->session->enterpriseInf->enterpriseCode,
			];

			if ($dataRequest->action == 'UPDATE_DATA') {
				$data['firstName'] = $list->names;
				$data['lastName'] = $list->lastName;
				$data['email'] = $list->email;
				$data['phone'] = $list->celPhone;
			}

			$dataList[] = $data;
		}

		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->password)
		);

		if (lang('CONF_HASH_PASS') == 'ON' || $this->session->autoLogin == 'false') {
			$password = md5($password);
		}

		$this->dataRequest->idOperation = 'operacionSeguimientoLoteCeo';
		$this->dataRequest->items = $dataList;
		$this->dataRequest->usuario = [
			'userName' => $this->session->userName,
			'password' => $password,
			'idProducto' => $this->session->productInf->productPrefix
		];
		$this->dataRequest->opcion = lang('SERVICES_ACTION_'.$dataRequest->action);

		$response = $this->sendToService('callWs_InquiriesActions');
		$balanceList = [];
		$failList = [];

		switch ($this->isResponseRc) {
			case 0:
				$this->response->title = lang('SERVICES_INQUIRY_'.$dataRequest->action);
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
				$this->response->success = TRUE;
				$responseList = isset($response->bean) ? json_decode($response->bean) : FALSE;

				if ($responseList && is_array($responseList)) {
					foreach ($responseList AS $cards) {
						$record = new stdClass();
						$record->cardNumber = substr($cards->numeroTarjeta, -6);
						$record->balance = isset($cards->saldo) ?  lang('GEN_CURRENCY').' '.$cards->saldo : '--';
						$balanceList[] = $record;

						if ($cards->rcNovoTrans != '0') {
							$this->response->code = 1;
							$failList[] = $cards->numeroTarjeta;
							$this->response->msg = 'No fue posible realizar la acción para';
						}
					}
				}

				if ($dataRequest->action == 'INQUIRY_BALANCE') {
					$this->response->code = 1;
					$this->response->success = false;
				}
			break;
			case -1:
				$this->response->title = lang('SERVICES_INQUIRY_'.$dataRequest->action);
				$this->response->msg = lang('RESP_PASSWORD_NO_VALID');
				$this->response->icon = lang('GEN_ICON_WARNING');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
			break;
			case -450:
				$this->response->title = lang('SERVICES_INQUIRY_'.$dataRequest->action);
				$this->response->msg = 'Alcanzaste el límite de consultas diarias';
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
			break;
		}

		$this->response->data['balanceList'] = $balanceList;
		$this->response->data['failList'] = $failList;

		return $this->responseToTheView('callWs_InquiriesActions');
	}
}
