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

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Transferencia maestra';
		$this->dataAccessLog->operation = 'Obtener lista de tarjetas';

		$this->dataRequest->idOperation = 'buscarTransferenciaM';
		$this->dataRequest->className = 'com.novo.objects.MO.TransferenciaMO';
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;
		$idPersonal = strtoupper($dataRequest->idNumber);
		if(lang('CONF_INPUT_UPPERCASE') == 'ON'){
			$idPersonal = $dataRequest->idNumber;
		}
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
					'id_ext_per' => $idPersonal
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
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->msg = lang('GEN_TABLE_SEMPTYTABLE');
				$this->response->data['btn1']['action'] = 'destroy';
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
				case 'CHECK_BALANCE':
				case 'TEMPORARY_LOCK':
				case 'TEMPORARY_UNLOCK':
					unset($card['montoTransaccion']);
				break;
				case 'CARD_ASSIGNMENT':
					unset($card['montoTransaccion']);
					$card['noTarjetaAsig'] = $cardsInfo->cardNumberAs;
				break;
			}

			$cardsList[] = $card;
		}

		switch ($dataRequest->action) {
			case 'CHECK_BALANCE':
				$this->dataAccessLog->operation = lang('GEN_CHECK_BALANCE');
				$this->dataRequest->idOperation = 'saldoTM';
			break;
			case 'CREDIT_TO_CARD':
				$this->dataAccessLog->operation = lang('GEN_CREDIT_TO_CARD');
				$this->dataRequest->idOperation = 'abonarTM';
			break;
			case 'DEBIT_TO_CARD':
				$this->dataAccessLog->operation = lang('GEN_DEBIT_TO_CARD');
				$this->dataRequest->idOperation = 'cargoTM';
			break;
			case 'TEMPORARY_LOCK':
				$this->dataAccessLog->operation = lang('GEN_TEMPORARY_LOCK');
				$this->dataRequest->idOperation = 'bloqueoTM';
			break;
			case 'TEMPORARY_UNLOCK':
				$this->dataAccessLog->operation = lang('GEN_TEMPORARY_UNLOCK');
				$this->dataRequest->idOperation = 'desbloqueoTM';
			break;
			case 'CARD_ASSIGNMENT':
				$this->dataAccessLog->operation = lang('GEN_CARD_ASSIGNMENT');
				$this->dataRequest->idOperation = 'reasignacionTM';
			break;
		}

		$this->dataRequest->className = 'com.novo.objects.MO.TransferenciaMO';
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
		$password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : FALSE;

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = $this->session->passWord ?: md5($password);
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
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data['btn1']['action'] = 'destroy';

				if ($dataRequest->action == 'TEMPORARY_LOCK' || $dataRequest->action == 'TEMPORARY_UNLOCK') {
					$blockType = $dataRequest->action == 'TEMPORARY_LOCK' ? 'Bloqueda' : 'Desbloqueda';
					$this->response->msg =  novoLang(lang('SERVICES_BLOCKING_CARD'), [$cardsList[0]['noTarjeta'], $blockType]);
					$this->response->update = TRUE;
				}

				if ($dataRequest->action == 'CARD_ASSIGNMENT') {
					$maskCards = maskString($cardsList[0]['noTarjetaAsig'], 4, 6);
					$this->response->msg =  novoLang(lang('SERVICES_ASSIGNMENT_CARD'), [$cardsList[0]['noTarjeta'], $maskCards]);
				}

				if ($dataRequest->action == 'CHECK_BALANCE') {
					$this->response->code = 0;
					foreach ($response->listadoTarjetas->lista as $key => $cards) {
						$record = new stdClass();
						$record->usersId = $cards->id_ext_per;
						$record->cardNumber = $cards->noTarjetaConMascara;
						$record->balance = isset($cards->saldos) ?  lang('GEN_CURRENCY').' '.currencyFormat($cards->saldos->disponible) : '--';
						$listResopnse[] = $record;

						if ($record->balance == '--') {
							$this->response->code = 4;
							$listFail[] = $cards->noTarjetaConMascara;
						}
					}

					if (count($listFail) > 0) {
						$this->response->code = 2;
						$this->response->msg = lang('SERVICES_BALANCE_NO_FOUND');
					}
				}

				if ($dataRequest->action == 'CREDIT_TO_CARD' || $dataRequest->action == 'DEBIT_TO_CARD') {
					foreach ($response->listadoTarjetas->lista as $key => $cards) {
						$record = new stdClass();
						$record->usersId = $cards->id_ext_per;
						$record->cardNumber = $cards->noTarjetaConMascara;
						$record->amount = isset($cards->montoTransaccion) ?  lang('GEN_CURRENCY').' '.$cards->montoTransaccion : '--';
						$listResopnse[] = $record;
					}

					$this->response->code = 2;
					$this->response->msg = lang('SERVICES_TRANSACTION_DATA');
				}

				$this->response->data['listResponse'] = $listResopnse;
				$this->response->data['listFail'] = $listFail;

				if (isset($response->maestroDeposito)) {
					$this->response->data['balance'] = lang('GEN_CURRENCY').' '.$response->maestroDeposito->saldoDisponible;
				}
			break;
			case -1:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -21:
			case -22:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_TRANSACTION_FAIL');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -33:
			case -100:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_BALANCE_NO_AVAILABLE');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -152:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_MIN_AMOUNT');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -153:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_MAX_WEEKLY_AMOUNT');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -154:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_MAX_DAILY_AMOUNT');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -155:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_NO_BALANCE');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -157:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_MAX_DAILY_OPERATION');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -242:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_MAX_OPERATION');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -267:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = novoLang(lang('SERVICES_BLOCKED_CARD'), $cardsList[0]['noTarjeta']);
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -429:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$maskCards = maskString($cardsList[0]['noTarjetaAsig'], 4, 6);
				$this->response->msg = novoLang(lang('SERVICES_CARD_BULK_AFFILIATED'), $maskCards);
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -459:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_PENDING_MEMBER_SHIP');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -460:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = lang('SERVICES_USER_BULK_CONFIRM');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -461:
				$this->response->title = lang('GEN_'.$dataRequest->action);
				$this->response->msg = novoLang(lang('SERVICES_CARD_BULK_CONFIRM'), $cardsList[0]['noTarjeta']);
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'destroy';
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

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Consulta de tarjetas';
		$this->dataAccessLog->operation = isset($dataRequest->action) ? 'Descargar archivo' : 'Obtener lista de tarjetas';

		$this->dataRequest->idOperation = isset($dataRequest->action) ? 'buscarTarjetasEmitidasExcel' : 'buscarTarjetasEmitidas';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoEmisionesMO';
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
					$this->response->icon =  lang('CONF_ICON_WARNING');
					$this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
					$this->response->data['btn1']['action'] = 'destroy';
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

		$className = 'com.novo.objects.MO.SeguimientoLoteMO';

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Consulta de tarjetas';
		$this->dataAccessLog->operation = lang('SERVICES_INQUIRY_'.$dataRequest->action);

		switch ($dataRequest->action) {
			case 'INQUIRY_BALANCE':
			case 'LOCK_CARD':
			case 'UNLOCK_CARD':
				$className = 'com.novo.business.lote.seguimiento.resources.NovoBusinessOperacionSeguimientoWS';
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

		$password = isset($dataRequest->password) ? $this->cryptography->decryptOnlyOneData($dataRequest->password) : FALSE;

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = $this->session->passWord ?: md5($password);
		}

		$this->dataRequest->idOperation = 'operacionSeguimientoLoteCeo';
		$this->dataRequest->className = $className;
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
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'destroy'
				];
				$this->response->success = TRUE;
				$responseList = $response->bean ?? FALSE;

				if ($responseList && is_array($responseList)) {
					foreach ($responseList AS $cards) {
						$record = new stdClass();
						$record->cardNumber = substr($cards->numeroTarjeta, -6);
						$record->balance = isset($cards->saldo) ?  lang('GEN_CURRENCY').' '.currencyformat($cards->saldo) : '--';
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
					$this->response->success = FALSE;
				}
			break;
			case -1:
				$this->response->title = lang('SERVICES_INQUIRY_'.$dataRequest->action);
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'destroy'
				];
			break;
			case -450:
				$this->response->title = lang('SERVICES_INQUIRY_'.$dataRequest->action);
				$this->response->msg = 'Alcanzaste el límite de consultas diarias';
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'destroy'
				];
			break;
		}

		$this->response->data['balanceList'] = $balanceList;
		$this->response->data['failList'] = $failList;

		return $this->responseToTheView('callWs_InquiriesActions');
	}

		/**
	 * @info Método para consulta tarjetas en giros comerciales
	 * @author Diego Acosta García
	 * @date July 15th, 2020
	 */

	public function callWs_commercialTwirls_Services($dataRequest)
	{
		log_message('INFO', 'NOVO Services Model: commercialTwirls Method Initialized');

		$this->dataAccessLog->modulo = 'servicios';
		$this->dataAccessLog->function = 'custom_mcc';
		$this->dataAccessLog->operation = 'customMcc';

		$this->dataRequest->idOperation = 'customMcc';
		$this->dataRequest->className = 'com.novo.objects.MO.GiroComercialMO';
		$this->dataRequest->opcion = 'find_mcc';
		$this->dataRequest->companyId = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->product = $this->session->productInf->productPrefix;
		$this->dataRequest->cards = [
			['numberCard' =>  $dataRequest->cardNumber,
			'rc' => 0]
		];
		$this->dataRequest->usuario = [
			'userName' => $this->session->userName,
			'envioCorreoLogin'=> false,
			'guardaIp' => false,
			'rc' => 0
		];

		$response = $this->sendToService('callWs_commercialTwirls');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$responseBean = $response->bean->cards[0];
				$dataTwirls = new stdClass();
				$shops = new stdClass();
				$dataTwirls->updateDate =  $responseBean->datetimeLastUpdate;
				$dataTwirls->cardNumberP =  maskString($responseBean->numberCard, 4, 6);
				$dataTwirls->customerName =  $responseBean->personName;
				$dataTwirls->documentId =  $responseBean->personId;
				$shops = (array)$responseBean->mccItems;

				foreach ($shops as $key => $value) {
					 $shops[lang('SERVICES_NAMES_PROPERTIES')[$key]] = $value;
					 unset($shops[$key]);
				 };

				$this->response->data['dataTwirls'] = (array)$dataTwirls;
				$this->response->data['shops'] = (array)$shops;
			break;
			case -438:
				$shops = new stdClass();
				$this->response->data['shops']= $response->bean->cards[0];
				$this->response->title = lang('GEN_COMMERCIAL_TWIRLS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
        $this->response->data['btn1']['action'] = 'close';
				switch ($response->bean->cards[0]->rc) {
					case -266:
						$this->response->msg = 	novoLang(lang('SERVICES_TWIRLS_TEMPORARY_BLOCKED_CARD'), maskString( $dataRequest->cardNumber, 4, 6));
					break;
					case -307:
						$this->response->msg = 	novoLang(lang('SERVICES_TWIRLS_PERMANENT_BLOCKED_CARD'), maskString( $dataRequest->cardNumber, 4, 6));
					break;
					case -439:
						$this->response->msg = 	novoLang(lang('SERVICES_NO_FOUND_REGISTRY'), maskString( $dataRequest->cardNumber, 4, 6));
					break;
					case -440:
					case -441:
						$this->response->msg = 	novoLang(lang('SERVICES_TWIRLS_NO_AVAILABLE_CARD'), maskString( $dataRequest->cardNumber, 4, 6));
					break;
					case -197:
						$this->response->msg = 	novoLang(lang('SERVICES_TWIRLS_EXPIRED_CARD'), maskString( $dataRequest->cardNumber, 4, 6));
					break;
				}
      break;
		}

		return $this->responseToTheView('callWs_commercialTwirls');
	}

		/**
	 * @info Método actualización de tarjetas en giros comerciales
	 * @author Diego Acosta García
	 * @date July 16th, 2020
	 */

	public function callWs_updateCommercialTwirls_Services($dataRequest)
	{
		log_message('INFO', 'NOVO Services Model: updateCommercialTwirls Method Initialized');

		$this->dataAccessLog->modulo = 'servicios';
		$this->dataAccessLog->function = 'custom_mcc';
		$this->dataAccessLog->operation = 'customMcc';

		$this->dataRequest->idOperation = 'customMcc';
		$this->dataRequest->className = 'com.novo.objects.MO.GiroComercialMO';
		$this->dataRequest->opcion = 'act_mcc';
		$this->dataRequest->companyId = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->product = $this->session->productInf->productPrefix;
		foreach ((object)lang('SERVICES_NAMES_PROPERTIES') as $key => $value) {
    	$val[$key] = $dataRequest->$value;
		}
		$this->dataRequest->cards = [
			['numberCard' => $dataRequest->cardNumber,
			'mccItems' => $val,
			'rc' => 0]
		];

		$password = isset($dataRequest->passwordAuth) ? $this->cryptography->decryptOnlyOneData($dataRequest->passwordAuth) : FALSE;

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = $this->session->passWord ?: md5($password);
		}

		$this->dataRequest->usuario = [
			'userName' => $this->session->userName,
			'password' => $password,
			'envioCorreoLogin'=> false,
			'guardaIp' => false,
			'isDriver' => 0,
			'rc' => 0
		];

		$response = $this->sendToService('callWs_updateCommercialTwirls');

		switch($this->isResponseRc) {
			case 0:
				$this->response->title = lang('GEN_COMMERCIAL_TWIRLS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_SUCCESS');
				$this->response->success = TRUE;
        $this->response->msg = 	lang('RESP_SUCCESSFULL_UPDATE_TWIRLS');
        $this->response->data['btn1']['action'] = 'close';
			break;
			case -1:
				$this->response->title = lang('GEN_COMMERCIAL_TWIRLS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -146:
				$this->response->title = lang('GEN_COMMERCIAL_TWIRLS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = lang('RESP_NO_UPDATE_REGISTRY');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -438:
				$this->response->title = lang('GEN_COMMERCIAL_TWIRLS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = lang('RESP_REJECTED_REGISTRY');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -65:
				$this->response->code = 2;
				$this->response->title = lang('GEN_COMMERCIAL_TWIRLS_TITTLE');
				$this->response->msg= lang('SERVICES_TWIRLS_NO_UPDATE');

				foreach ((array)$response->bean->cards[0]->mccItems as $key => $value) {
					$mcc[lang('SERVICES_NAME_PROPERTIES_VIEW')[$key]] = $value;
					unset($mcc[$key]);
				};

				$this->response->data= $mcc;
			break;
		}

		return $this->responseToTheView('callWs_updateCommercialTwirls');
	}
	/**
	 * @info Método para obtener formulario limites transaccionales
	 * @author Diego Acosta García
	 * @date July 21th, 2020
	 */
	public function callWs_transactionalLimits_Services($dataRequest)
	{
		log_message('INFO', 'NOVO Services Model: transactionalLimits Method Initialized');

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Limites';
		$this->dataAccessLog->operation = 'Consultar Limites de tarjeta';

		$this->dataRequest->idOperation = 'consultarLimites';
		$this->dataRequest->className = 'com.novo.objects.TO.LimitesTO';
		$this->dataRequest->opcion = 'consultar';
		$this->dataRequest->idEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->prefix = $this->session->productInf->productPrefix;
		$this->dataRequest->cards = [
			['card' =>  $dataRequest->cardNumber,
			'personId' => '',
			'personName' => '',
			'lastUpdate' => ''
			]
		];
		$this->dataRequest->usuario = [
			'userName' => $this->session->userName
		];


		$response = $this->sendToService('callWs_transactionalLimits');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$responseBean = $response->bean->cards[0];
				$dataLimits = new stdClass();
				$limits = new stdClass();
				$dataLimits->updateDate =  $responseBean->lastUpdate;
				$dataLimits->cardNumberP =  maskString($responseBean->card, 4, 6);
				$dataLimits->customerName =  $responseBean->personName;
				$dataLimits->documentId =  $responseBean->personId;
				$limits = (array)$responseBean->parameters;

				foreach ($limits as $key => $value) {
					 $limits[lang('SERVICES_NAMES_PROPERTIES_LIMITS')[$key]] = $value;
					 unset($limits[$key]);
				 };

				$this->response->data['dataLimits'] = (array)$dataLimits;
				$this->response->data['limits'] = (array)$limits;

			break;
			case -447:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = lang('SERVICES_LIMITS_NO_REGISTRY');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -448:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = novoLang(lang('SERVICES_NO_FOUND_REGISTRY'), maskString( $dataRequest->cardNumber, 5, 6));
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -455:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = novoLang(lang('SERVICES_TWIRLS_NO_AVAILABLE_CARD'), maskString( $dataRequest->cardNumber, 5, 6));
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -444:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = lang('SERVICES_TWIRLS_NO_FOUND_REGISTRY');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -454:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = novoLang(lang('SERVICES_TWIRLS_TEMPORARY_BLOCKED_CARD'), maskString( $dataRequest->cardNumber, 5, 6));
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -330:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = novoLang(lang('SERVICES_TWIRLS_EXPIRED_CARD'), maskString( $dataRequest->cardNumber, 5, 6));
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -307:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = novoLang(lang('SERVICES_TWIRLS_PERMANENT_BLOCKED_CARD'), maskString( $dataRequest->cardNumber, 5, 6));
				$this->response->data['btn1']['action'] = 'close';
			break;
		}

		return $this->responseToTheView('callWs_transactionalLimits');
	}
	/**
	 * @info Método para la actualizacion de limites transaccionales
	 * @author Diego Acosta García
	 * @date July 21th, 2020
	 */
	public function callWs_updateTransactionalLimits_Services($dataRequest)
	{
		log_message('INFO', 'NOVO Services Model: updateTransactionalLimits Method Initialized');

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Limites';
		$this->dataAccessLog->operation = 'Actualizar Limites de tarjeta';

		$this->dataRequest->idOperation = 'actualizarLimites';
		$this->dataRequest->className = 'com.novo.objects.TO.LimitesTO';
		$this->dataRequest->opcion = 'actualizar';
		$this->dataRequest->idEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->prefix = $this->session->productInf->productPrefix;

		$password = isset($dataRequest->passwordAuth) ? $this->cryptography->decryptOnlyOneData($dataRequest->passwordAuth) : FALSE;

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = $this->session->passWord ?: md5($password);
		}

		foreach ((array)lang('SERVICES_NAMES_PROPERTIES_LIMITS') as $key => $val) {
			$cards[$key] = (int)$dataRequest->$val;
		};

		foreach ($cards as &$valor) {
			if ($valor == '') {
				$valor = '0';
			}
		};

		$this->dataRequest->cards = [
			[
				'card' =>  $dataRequest->cardNumber,
				'personId' => '',
				'personName' => '',
				'parameters' => $cards
			]
		];

		$this->dataRequest->usuario = [
			'userName' => $this->session->userName,
			'password' => $password
		];

		$response = $this->sendToService('callWs_updateTransactionalLimits');

		switch($this->isResponseRc) {
			case 0:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_SUCCESS');
        $this->response->msg = 	lang('RESP_SUCCESSFULL_UPDATE_LIMITS');
        $this->response->success = TRUE;
        $this->response->data['btn1']['action'] = 'close';
			break;
			case -1:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -449:
			case -450:
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = lang('SERVICES_LIMITS_NO_UPDATE');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -456:
				//Límites que no deben ser mayor a otro según configuración
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = $response->msg;
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -457:
				//Límite menor supera al definido para el producto
				$this->response->title = lang('GEN_TRANSACTIONAL_LIMITS_TITTLE');
				$this->response->icon =  lang('CONF_ICON_WARNING');
				$this->response->msg = $response->msg;
				$this->response->data['btn1']['action'] = 'close';
			break;
		}

		return $this->responseToTheView('callWs_updateTransactionalLimits');
	}
	/**
	 * @info Método para consultar el saldo de la cuenta maestra
	 * @author J. Enrique Peñaloza Piñero
	 * @date September 16th, 2020
	 */
	public function CallWs_MasterAccountBalance_Services($dataRequest)
	{
		log_message('INFO', 'NOVO Services Model: MasterAccountBalance Method Initialized');

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Transferencia maestra';
		$this->dataAccessLog->operation = 'Consulta de saldo';

		$this->dataRequest->idOperation = 'saldoCuentaMaestraTM';
		$this->dataRequest->className = 'com.novo.objects.TOs.TarjetaTO';
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;

		$response = $this->sendToService('CallWs_MasterAccountBalance');

		$this->response->code = 0;
		$this->response->data->info['balance'] = '';
		$this->response->data->info['balanceTxt'] = '';
		$this->response->data->info['fundingAccount'] = '';

		switch ($this->isResponseRc) {
			case 0:
				$this->response->data->info['balanceText'] = 'Saldo Disponible: ';
				$this->response->data->info['balance'] = lang('GEN_CURRENCY').' '.currencyFormat($response->maestroDeposito->saldoDisponible);
				$this->response->data->info['fundingAccount'] = $response->maestroDeposito->cuentaFondeo ?? '';

				$this->response->data->params['balance'] = (float )$response->maestroDeposito->saldoDisponible;
				$this->response->data->params['dailyOper']['quantity'] = (int) $response->maestroDeposito->cantidadTranxDia->lista[0]->idCuenta ?? '';
				$this->response->data->params['dailyOper']['amount'] = $response->maestroDeposito->cantidadTranxDia->lista[0]->montoOperacion ?? '';
				$this->response->data->params['weeklyOper']['quantity'] = (int) $response->maestroDeposito->cantidadTranxSemana->lista[0]->idCuenta ?? '';
				$this->response->data->params['weeklyOper']['amount'] = $response->maestroDeposito->cantidadTranxSemana->lista[0]->montoOperacion ?? '';

				if (isset($response->maestroDeposito->parametrosRecarga)) {
					unset(
						$response->maestroDeposito->parametrosRecarga->idExtEmp,
						$response->maestroDeposito->parametrosRecarga->acprefix,
						$response->maestroDeposito->parametrosRecarga->usuarioReg,
						$response->maestroDeposito->parametrosRecarga->tipoComision,
						$response->maestroDeposito->parametrosRecarga->tipoFactura,
					);
					$this->response->data->params['rechargeParams'] = $response->maestroDeposito->parametrosRecarga ?? '';
				}

				$this->response->data->params = base64_encode(json_encode($this->cryptography->encrypt($this->response->data->params)));
			break;
			case -233:

			break;
			case -251:

			break;
			case -402:

			break;
			default:
				$this->response->code = 4;
		}

		return $this->responseToTheView('CallWs_MatesaccountBlanace');
	}
	/**
	 * @info Método obtener clave de autorización
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 08th, 2020
	 */
	public function CallWs_AuthorizationKey_Services($dataRequest)
	{
		log_message('INFO', 'NOVO Services Model: AuthorizationKey Method Initialized');

		$this->dataAccessLog->modulo = 'Servicios';
		$this->dataAccessLog->function = 'Comunicación con tercero';
		$this->dataAccessLog->operation = 'Obtener clave de autorización';

		$this->dataRequest->idOperation = 'integracionBnt';
		$this->dataRequest->className = 'com.novo.objects.TOs.GestionUsuariosTO';
		$this->dataRequest->opcion = 'obtenerClaveAutorizacionBNT';
		$this->dataRequest->idEmpresa = $this->session->enterpriseInf->fiscalNumber;
		$this->dataRequest->idUsuario = $this->session->userName;
		$this->dataRequest->operacion = '101';
		$this->dataRequest->tipoOperacion = $dataRequest->action;
		$this->dataRequest->idServicio = '1260';

		$response = $this->sendToService('CallWs_AuthorizationKey');
		/* $response = json_decode('{"rc":0,"msg":"Proceso OK","bean":{"tranClave":"nuR8Q+ntN8ECmrW7+Oe4m7fPuWCeo5QXlu8QtXSt7EL9dEmSAdzVYvIjIlv1pC9WhAZSLHe8yjUMIcGoswH4bRt78FJPX6MU5nHxHa4o+hi3csUGqmI5T3j8ZxbxdmpQ0pHewHVRgLTqIqd6v8Mmqg\\u003d\\u003d","tranExitoso":true,"tranDescripcionError":""}}');
		$this->isResponseRc = 0; */
		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = [
					'authKey' => $response->bean->tranClave,
					'urlApp' => lang('CONF_AUTH_URL')[ENVIRONMENT][$this->session->enterpriseInf->thirdApp],
					'urlLoad' => lang('CONF_AUTH_LOADING_URL')
				];
			break;
			case -404:
				$this->response->title = $dataRequest->action;
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->msg = lang('GEN_GET_AUTH_USER_FAIL');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
			case -65:
				$this->response->title = $dataRequest->action;
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->msg = lang('GEN_GET_AUTH_KEY_FAIL');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
		}

		return $this->responseToTheView('CallWs_AuthorizationKey');
	}
}
