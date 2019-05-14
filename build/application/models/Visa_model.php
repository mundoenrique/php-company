<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Class:  visa_model
 * @package models
 * @INFO:   Clase para la comunicación con el servicio de los controles Visa
 * @author: J Enrique Peñaloza P
 * Date: 29/08/2017
 * Time: 10:30 am
 */
class Visa_model extends CI_Model {
	//Atributos de Clase
	protected $sessionId;
	protected $userName;
	protected $rc = 0;
	protected $rif;
	protected $token;
	protected $ip;
	protected $timeLog;
	protected $code;
	protected $title;
	protected $msg;
	protected $data;
	protected $response = [];

	public function __construct()
	{
		log_message('INFO', 'NOVO Visa Model Class Initialized');
		//Inicializar Atributos de clase
		$this->sessionId = $this->session->userdata('sessionId');
		$this->userName = $this->session->userdata('userName');
		$this->rif = $this->session->userdata('acrifS');
		$this->idProductoS = $this->session->userdata('idProductoS');
		$this->token = $this->session->userdata('token');
		$this->ip = $this->input->ip_address();
		$this->timeLog = date("m/d/Y H:i");
        //Incorporar languages
		$this->lang->load('servicios');
		$this->lang->load('dashboard');
		$this->lang->load('users');
		$this->lang->load('erroreseol');
		$this->lang->load('visa');
	}
    //Método para obtener la lista tarjetas asociadas
    public function callWsCardList($urlCountry, $dataRequest = NULL)
    {
	    log_message('INFO', '[' . $this->userName . '] DataRequest--->: ' .
	                        $dataRequest);


	    $paginar = TRUE;
	    $dataVisa = json_decode($dataRequest);
	    $draw = $dataVisa->draw;
	    $recordsTotal = 0;
	    $recordsFiltered = 0;
	    $tamanoPagina = $dataVisa->length;
	    $paginaActual = $dataVisa->start === '0' ? '1' :
		    ((int)$dataVisa->start / (int)$tamanoPagina) + 1 ;
	    $dni = $dataVisa->dni;
	    $tarjetaNro = $dataVisa->card;

	    $canal = 'ceo';
		$modulo = 'TM';
		$function = 'buscarTransferenciaM';
		$operacion = 'buscarTransferenciaM';
		$idOperation = 'buscarTarjetasVisa';
		$className = 'com.novo.objects.TOs.TarjetaTO';

		$logAcceso = np_hoplite_log($this->sessionId, $this->userName, $canal, $modulo,
		                            $function, $operacion, $this->rc, $this->ip, $this->timeLog);

		$data = json_encode([
			'idOperation' => $idOperation,
			'className' => $className,
			'rif' => $this->rif,
			'prefix' => $this->idProductoS,
			'noTarjeta' => $tarjetaNro,
			'id_ext_per' => $dni,
			'paginaActual' => $paginaActual,
			'tamanoPagina' => $tamanoPagina,
			'paginar'=> $paginar,
			'logAccesoObject' => $logAcceso,
			'token' => $this->token,
			'pais' => $urlCountry
		]);

		log_message('INFO',
		            '[' . $this->userName . '] REQUEST visa -- callWsCardList --> ' . $data);

		$dataEncrypt = np_Hoplite_Encryption($data);
		$request = json_encode([
			'bean' => $dataEncrypt,
			'pais' => $urlCountry
		]);
		$responseWs = np_Hoplite_GetWS('eolwebInterfaceWS', $request);
		$responseJson = np_Hoplite_Decrypt($responseWs);
		$responseWs = json_decode($responseJson);

		log_message('INFO', '[' . $this->userName . '] RESPONSE visa -- ' .
		                    'callWsCardList --> ' . json_encode($responseWs));

	    /*$responseWs = json_decode('{"rc":-150,"msg":"PROCESO OK"}');*/
		$this->data = $cardList = [];
		if($responseWs) {
			switch ($responseWs->rc) {
				case 0:
					foreach ($responseWs->lista as $key => $lista) {
						$cardList[$key]['cardNumber'] = $lista->noTarjetaConMascara;
						$cardList[$key]['clientID'] = $lista->id_ext_per;
						$cardList[$key]['clientName'] = $lista->NombreCliente;
						$cardList[$key]['expiracyDate'] = $lista->fechaExp;
					}
					$this->code = 0;
					$this->title = '';
					$recordsTotal = $responseWs->numeroTarjetas;
					$recordsFiltered = $responseWs->numeroTarjetas;
					$this->data = $cardList;
					break;

				case -29:
				case -61:
					$this->code = 3;
					$this->title = lang('SYSTEM_NAME');
					$this->msg = lang('ERROR_(-29)');
					break;

				case -3:
					$this->code = 3;
					$this->title = lang('SYSTEM_NAME');
					$this->msg = lang('ERROR_GENERICO_USER');
					break;

				case -150:
					$this->code = 2;
					$this->title = lang('CONVIS');
					$this->msg = lang('VISA_NON_RESULT');
					break;

				case -235:
					$this->code = 2;
					$this->title = lang('CONVIS');
					$this->msg = 'El servicio no está disponible, por favor intente más tarde';
					break;

				default:
					$this->code = 2;
					$this->title = lang('CONVIS');
					$this->msg = lang('ERROR_(' . $responseWs->rc . ')');
					break;
			}
		} else {
			$this->code = 3;
			$this->title = lang('SYSTEM_NAME');
			$this->msg = lang('ERROR_GENERICO_USER');
		}

		$this->response = [
			'code' => $this->code,
			'title' => $this->title,
			'msg' => $this->msg,
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $this->data
		];
		if($this->code === 3) {
			$this->session->sess_destroy();
		}

		return json_encode($this->response);
    }
	//----------------------------------------------------------------------------------------------

    //Método para obtener la lista tarjetas asociadas
    public function callWsCardControls($urlCountry, $dataRequest)
    {
	    log_message('INFO', '[' . $this->userName . '] DataRequest--->: ' .
	                        $dataRequest);
	    $dataRequest = json_decode($dataRequest);
		$dni = $dataRequest->dni;
		$card = $dataRequest->card;
		$canal = 'ceo';
		$modulo = 'Controles';
		$function = 'Controles_VISA';
		$operacion = 'buscarControles';
		$idOperation = 'buscarControlesTarjetaVisa';
		$className = 'com.novo.visa.TO.VisaRequestTO';

		$logAcceso = np_hoplite_log(
			$this->sessionId, $this->userName, $canal, $modulo, $function, $operacion, $this->rc,
			$this->ip, $this->timeLog
		);

		$data = json_encode([
			'idOperation' => $idOperation,
			'className' => $className,
			'id_ext_per' => $dni,
			'id_ext_emp' => $this->rif,
			'idprograma' => $this->idProductoS,
			'logAccesoObject' => $logAcceso,
			'token' => $this->token,
			'pais' => $urlCountry
		]);

		log_message('INFO', '[' . $this->userName . '] REQUEST visa -- ' .
		                    'callWsCardControls --> ' . $data);

		$dataEncrypt = np_Hoplite_Encryption($data);
		$request = json_encode([
			'bean' => $dataEncrypt,
			'pais' => $urlCountry
		]);
		$responseWs = np_Hoplite_GetWS('eolwebInterfaceWS', $request);
		$responseJson = np_Hoplite_Decrypt($responseWs);
		$responseWs = json_decode($responseJson);

		log_message('INFO', '[' . $this->userName . '] RESPONSE visa -- callWsCardControls --> {"rc":' .
		                    json_encode($responseWs->rc) . ',"msg":' . json_encode($responseWs->msg) . '}');

		if($responseWs->rc === '0') {
			log_message('INFO', '[' . $this->userName . '] RESPONSE BEAN -- callWsCardControls --> ' .
			                    $responseWs->bean);
		}

	    /*$responseWs = json_decode('{"rc":-3,"msg":"PROCESO OK"}');*/
	    $this->data = $controlList = [];
	    if($responseWs) {
		    switch ($responseWs->rc) {
			    case 0:
			    	$this->code = 0;
				    $this->data = json_decode($responseWs->bean);
				    $this->data->dni = $dni;
				    $this->data->card = $card;
				    break;

			    case -150:
				    $this->code = 2;
				    $this->title = lang('CONVIS');
				    $this->msg = lang('VISA_NON_RESULT');
				    break;

			    case -345:
			    case -346:
				    $this->code = 2;
				    $this->title = lang('CONVIS');
				    $this->msg = lang('VISA_COUNT_NO_FOUND');
				    break;

			    case -347:
				    $this->code = 2;
				    $this->title = lang('CONVIS');
				    $this->msg = lang('VISA_ERROR_CONTROLS');
				    break;

			    case -29:
			    case -61:
				    $this->code = 3;
				    $this->title = lang('SYSTEM_NAME');
				    $this->msg = lang('ERROR_(-29)');
				    break;

			    case -3:
			    case -20:
			    case -33:
				    $this->code = 3;
				    $this->title = lang('SYSTEM_NAME');
				    $this->msg = lang('ERROR_GENERICO_USER');
				    break;

			    default:
				    $this->code = 2;
				    $this->title = lang('CONVIS');
				    $this->msg = lang('ERROR_(' . $responseWs->rc . ')');
				    break;
		    }
	    } else {
		    $this->code = 3;
		    $this->title = lang('SYSTEM_NAME');
		    $this->msg = lang('ERROR_GENERICO_USER');
	    }

	    $this->response = [
		    'code' => $this->code,
		    'title' => $this->title,
		    'msg' => $this->msg,
		    'data' => $this->data
	    ];
	    if($this->code === 3) {
		    $this->session->sess_destroy();
	    }

	    return json_encode($this->response);

	}
	//----------------------------------------------------------------------------------------------

	//Método para obtener la actualizar los controles
	public function callWsUpdateControls($urlCountry, $dataRequest)
	{
		log_message('INFO', '[' . $this->userName . '] DataRequest--->: ' .
		                    $dataRequest);
		$dataRequest = json_decode($dataRequest);
		$dni = $dataRequest->dni;
		$firstDate = $dataRequest->firstDate;
		$lastDate = $dataRequest->lastDate;
		$controls = $dataRequest->controls;
		$canal = 'ceo';
		$modulo = 'Controles';
		$function = 'Controles_VISA';
		$operacion = 'buscarControles';
		$idOperation = 'actualizarControlesTarjetaVisa';
		$className = 'com.novo.visa.TO.VisaRequestTO';
		$create = [
			'action' => 'A',
			'rules' => []
		];
		$update = [
			'action' => 'U',
			'rules' => []
		];
		$delete =[
			'action' => 'D',
			'rules' => []
		];

		$logAcceso = np_hoplite_log(
			$this->sessionId, $this->userName, $canal, $modulo, $function, $operacion, $this->rc,
			$this->ip, $this->timeLog
		);

		foreach($controls AS $ruleCode => $overRides) {
			$coderule = $ruleCode === 'not_fuel' ? 'not fuel' : $ruleCode;
			switch($overRides->action) {
				case 'A':
					$rules = [
						'ruleCode' => $coderule,
						'overrides' => [],
					];

					if(isset($overRides->overrides)) {
						foreach($overRides->overrides AS $override) {
							$code = $override->code;
							$value = (int)$override->value;
							$overrides = [
								'sequence' => 0,
								'overrideCode' => $code,
								'overrideValue' =>  $value
							];
							array_push(
								$rules['overrides'],
								$overrides
							);
						}

					}
					array_push(
						$create['rules'],
						$rules
					);
					break;
				case 'U':
					$rules = [
						'ruleCode' => $coderule,
						'overrides' => [],
					];

					if(isset($overRides->overrides)) {
						foreach($overRides->overrides AS $override) {
							$code = $override->code;
							$value = (int)$override->value;
							$overrides = [
								'sequence' => 0,
								'overrideCode' => $code,
								'overrideValue' =>  $value
							];
							array_push(
								$rules['overrides'],
								$overrides
							);
						}

					}
					array_push(
						$update['rules'],
						$rules
					);
					break;
				case 'D':
					$rules = [
						'ruleCode' => $coderule,
						'overrides' => [],
					];

					if(isset($overRides->overrides)) {
						foreach($overRides->overrides AS $override) {
							$code = $override->code;
							$value = (int)$override->value;
							$overrides = [
								'sequence' => 0,
								'overrideCode' => $code,
								'overrideValue' =>  $value
							];
							array_push(
								$rules['overrides'],
								$overrides
							);
						}

					}
					array_push(
						$delete['rules'],
						$rules
					);
					break;
			}
		}

		$controlsList = [
			'startDate' => $firstDate,
		    'endDate' => $lastDate,
		    'timeZone' => 'UTC-6',
			'rulesSet' => [$create, $update, $delete],
		];

		$data = json_encode([
            'idOperation' => $idOperation,
            'className' => $className,
            'id_ext_per' => $dni,
            'company_id' => $this->rif,
            'idprograma' => $this->idProductoS,
            'paymentControlDetails' => [$controlsList],
            'logAccesoObject' => $logAcceso,
            'token' => $this->token,
            'pais' => $urlCountry
		 ]);

		log_message('INFO', '[' . $this->userName . '] REQUEST visa -- ' .
		                    'callWsCardControls --> ' . $data);

		$dataEncrypt = np_Hoplite_Encryption($data);
		$request = json_encode([
			'bean' => $dataEncrypt,
			'pais' => $urlCountry
		 ]);
		$responseWs = np_Hoplite_GetWS('eolwebInterfaceWS', $request);
		$responseJson = np_Hoplite_Decrypt($responseWs);
		$responseWs = json_decode($responseJson);

		log_message('INFO', '[' . $this->userName . '] RESPONSE visa -- ' .
		                    'callWsCardList --> ' . json_encode($responseWs));

		if($responseWs) {
			switch ($responseWs->rc) {
				case 0:
					$this->code = 0;
					$this->data = $responseWs;
					$this->title = lang('CONVIS');
					$this->msg = lang('VISA_UPDATE_SUCCESS');

					break;
				case -29:
				case -61:
					$this->code = 3;
					$this->title = lang('SYSTEM_NAME');
					$this->msg = lang('ERROR_(-29)');
					break;
				case -3:
				case -33:
					$this->code = 3;
					$this->title = lang('SYSTEM_NAME');
					$this->msg = lang('ERROR_GENERICO_USER');
					break;
				case -150:
					$this->code = 2;
					$this->title = lang('CONVIS');
					$this->msg = lang('VISA_NON_RESULT');
					break;

				default:
					$this->code = 2;
					$this->title = lang('CONVIS');
					$this->msg = lang('ERROR_(' . $responseWs->rc . ')');
					break;
			}
		} else {
			$this->code = 3;
			$this->title = lang('SYSTEM_NAME');
			$this->msg = lang('ERROR_GENERICO_USER');
		}

		$this->response = [
			'code' => $this->code,
			'title' => $this->title,
			'msg' => $this->msg,
			'data' => $this->data
		];
		if($this->code === 3) {
			$this->session->sess_destroy();
		}

		return json_encode($this->response);

	}	
}