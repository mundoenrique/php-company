<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Users_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function callWSConsultarSucursales($urlCountry ,$rif, $paginaActual, $cantItems, $paginar){

		$this->lang->load('erroreseol');

		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');

		$idOperation = "getConsultarSucursales";
		$classname = "com.novo.objects.MO.ListadoSucursalesMO";
		$canal = "ceo";
		$modulo = "getConsultarSucursales";
		$funcion = "getConsultarSucursales";
		$operation = "getConsultarSucursales";

		$ip= $this->input->ip_address();
		$timeLog= date("m/d/Y H:i");

		$sessionId = $this->session->userdata('sessionId');			
		$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$funcion,$operation,0,$ip,$timeLog);

		$data = array(
			"pais" => $urlCountry,
			"idOperation" => $idOperation,
			"className" => $classname,
			"lista"=>array(array(
				"rif"=> $rif
				)),
			"paginaActual"=> $paginaActual,
			"tamanoPagina"=> $cantItems,
			"paginar"=> $paginar,
			"logAccesoObject" => $logAcceso,
			"token" => $token
			);


		$data = json_encode($data,JSON_UNESCAPED_UNICODE);

		log_message('info','sucursales data request => '.$data);

		$dataEncry = np_Hoplite_Encryption($data);
		$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );		
		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response);
		$response = json_decode(utf8_encode($jsonResponse));

		if($response){
			//log_message('info','sucursales '.$response->rc.'/'.$response->msg);

			if($response->rc==0){

				log_message('info','sucursales data response => '.json_encode($response->lista,JSON_UNESCAPED_UNICODE));

				return $response->lista;
			}else{
				if($response->rc==-61 || $response->rc==-29){
					$this->session->sess_destroy();
					$this->session->unset_userdata($this->session->all_userdata());
					$codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc"=> $response->rc);
				}else if($response->rc==-150){
					$codigoError = array('ERROR' => lang('ERROR_(-150)'), "rc"=> $response->rc, 'paisTo'=>$response->paisTo);	
				}
				else{
					$codigoError = lang('ERROR_('.$response->rc.')');
					if(strpos($codigoError, 'Error')!==false){
						$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
					}else{
						$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
					}
				}	
				return $codigoError;

			}		

		}else{
			log_message('info','sucursales NO WS ');
			return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
		}

	}

}