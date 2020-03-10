<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Innominadas_Model extends CI_Model {

	public function __construct()
	{
		log_message('INFO', 'NOVO Plantilla Model Class Initialized');
	}

	public function callWSCreateInnominadas($urlCountry, $cantReg, $monto,  $lembozo1, $lembozo2, $codSucursal, $password,  $fechaExp){

		$this->lang->load('erroreseol');

		$username = $this->session->userdata('userName');
		$codgrupo = $this->session->userdata('codigoGrupo');
		$idempresa = $this->session->userdata('acrifS');
		$accodciaS = $this->session->userdata('accodciaS');
		$idProductoS = $this->session->userdata('idProductoS');

		$token = $this->session->userdata('token');

		$idOperation = "createCuentasInnominadas";
		$classname = "com.novo.objects.MO.ListadoSucursalesMO";
		$canal = "ceo";
		$modulo = "createCuentasInnominadas";
		$funcion = "createCuentasInnominadas";
		$operation = "createCuentasInnominadas";

		$ip= $this->input->ip_address();
		$timeLog= date("m/d/Y H:i");

		$sessionId = $this->session->userdata('sessionId');
		$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$funcion,$operation,0,$ip,$timeLog);

		$cantReg = (!empty($cantReg)) ? $cantReg : '';
		$monto = (!empty($monto)) ? $monto : '';
		$lembozo1 = (!empty($lembozo1)) ? $lembozo1 : '';
		$lembozo2 = (!empty($lembozo2)) ? $lembozo2 : '';
		$codSucursal = (!empty($codSucursal)) ? $codSucursal : '';
		$fechaExp = (!empty($fechaExp)) ? $fechaExp : '';

		$data = array(
			"pais" => $urlCountry,
			"idOperation" => $idOperation,
			"className" => $classname,
			"lotesTO" => array(
				"usuario" => $username,
				"codCia" => $accodciaS,
				"codGrupo" => $codgrupo,
				"cantRegistros" => $cantReg,
				"idEmpresa" => $idempresa,
				"monto" => "0",
				"idTipoLote" => "3",
				"formato" => "00",
				"tipoLote" => "INNOMINADAS",
				"codProducto" => $idProductoS,
				"fechaValor" => date('d/m/Y h:i:s'),
				"lineaEmbozo1" => $lembozo1,
				"lineaEmbozo2" => $lembozo2,
				"accanal" => "WEB",
				"reproceso" => true,
				"sucursalCod" => $codSucursal,
				"ubicacion" => "EM",
				"password" => $password,
				"fechaExp" => $fechaExp,
				"destinoEmb" => "01"
				),
			"logAccesoObject" => $logAcceso,
			"token" => $token
			);
			//"08/01/2014 00:00:00",

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);

		log_message('info','solicitud inno data request => '.$data);

		$dataEncry = np_Hoplite_Encryption($data, 'callWSCreateInnominadas');
		$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSCreateInnominadas');

		log_message('info','solicitud data response => '.$jsonResponse);

		$response = json_decode($jsonResponse);
		if($response){
			if($response->rc==0){
				return $response->rc;
			}else{

				//log_message('info','solicitud data response => '.json_encode($response,JSON_UNESCAPED_UNICODE));

				if($response->rc==-61 || $response->rc==-29){
					$this->session->sess_destroy();
					$codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc"=> $response->rc);
				}else	if($response->rc==-1){
				$codigoError = array('ERROR' => lang('MSG_INVALID_PASS'), "rc"=> $response->rc);
			}else if($response->rc==-150){
					$codigoError = array('ERROR' => lang('ERROR_(-150)'), "rc"=> $response->rc, 'paisTo'=>$response->paisTo);
				}else{
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
			log_message('info','solicitud => ' + $response);
			return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
		}
	}

	public function callWSReporteInnominadas($urlCountry, $numlote){

		$this->lang->load('erroreseol');

		$token = $this->session->userdata('token');
		$idProductoS = $this->session->userdata('idProductoS');
		$idempresa = $this->session->userdata('acrifS');

		$idOperation = "generarReporteTarjetasInnominadas";
		$classname = "com.novo.objects.MO.ListadoLotesMO";
		$canal = "ceo";
		$modulo = "ReporteTarjetasInnominadas";
		$funcion = "ReporteTarjetasInnominadas";
		$operation = "ReporteTarjetasInnominadas";

        $userName = $this->session->userdata('userName');
        $sessionId = $this->session->userdata('sessionId');
        $timeLog   = date("m/d/Y H:i");
        $ip= $this->input->ip_address();

		$sessionId = $this->session->userdata('sessionId');
		$logAcceso = np_hoplite_log($sessionId,$userName,$canal,$modulo,$funcion,$operation,0,$ip,$timeLog);

		$data = array(
			"pais"=>$urlCountry,
			"idOperation"=>$idOperation,
			"className"=>$classname,
			"tarjetasInnominadas"=>array(array(
						"numLote"=>$numlote,
						"idExtEmp"=>$idempresa,
						"estatus"=>"0",
						"enmascarar"=> false
			)),
			"idProducto" => $idProductoS,
			"logAccesoObject"=>$logAcceso,
			"token"=>$token
			);

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);

		//log_message('info','lista inno_en_proceso 1 data request=>'.$prueba);
		log_message('info','inno_report_xls data request=>'.$data);

		$dataEncry = np_Hoplite_Encryption($data, 'callWSReporteInnominadas');
		$data = array('bean'=>$dataEncry, 'pais' =>$urlCountry );

		log_message('info','inno_report_xls 2 data request=>'.json_encode($data,JSON_UNESCAPED_UNICODE));

		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSReporteInnominadas');
		$response = json_decode($jsonResponse);

		if($response){
			//log_message('info','inno_report_xls data response=>'.$response);

			if($response->rc==0){
				$response = json_decode($response->bean);
				return $response;
			} else {

				log_message('info','inno_report_xls DATA Response ======> '.json_encode($response,JSON_UNESCAPED_UNICODE));

				if ($response->rc==-61 || $response->rc==-29) {
					$this->session->sess_destroy();
					$codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc"=> $response->rc);
				} else if($response->rc==-150) {
					$codigoError = array('ERROR' => lang('ERROR_(-150)'), "rc"=> $response->rc, 'paisTo'=>$response->paisTo);
				}else if($response->rc==-137) {
                    $codigoError = array('ERROR' => lang('ERROR_(-137)'), "rc"=> $response->rc);
                } else {
					$codigoError = lang('ERROR_('.$response->rc.')');
					if(strpos($codigoError, 'Error')!==false){
						$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
					}else{
						$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
					}
				}
            }
        } else {
			log_message('info','inno_report_xls => ' + $response);
			$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
		}
        return $codigoError;

	}

	public function callWSListaInnominadasEnProc($urlCountry, $cestatus, $exoboolean, $acnumlote, $dtfechorcargaIni, $dtfechorcargaFin){

		$this->lang->load('erroreseol');

		$token = $this->session->userdata('token');

		$idOperation = "getListadoLotes";
		$classname = "com.novo.objects.MO.ListadoLotesMO";
		$canal = "ceo";
		$modulo = "getListadoLotes";
		$funcion = "ListadoLotes";
		$operation = "ListadoLotes";

		$idempresa = $this->session->userdata('acrifS');
		$idProductoS = $this->session->userdata('idProductoS');

		$acnumlote = (!empty($acnumlote)) ? $acnumlote : '';
		$dtfechorcargaIni = (!empty($dtfechorcargaIni)) ? $dtfechorcargaIni : '';
		$dtfechorcargaFin = (!empty($dtfechorcargaFin)) ? $dtfechorcargaFin : '';

        $userName = $this->session->userdata('userName');
        $sessionId = $this->session->userdata('sessionId');
        $timeLog   = date("m/d/Y H:i");
        $ip= $this->input->ip_address();

		$sessionId = $this->session->userdata('sessionId');
		$logAcceso = np_hoplite_log($sessionId,$userName,$canal,$modulo,$funcion,$operation,0,$ip,$timeLog);

		$data = array(
			"pais"=>$urlCountry,
			"idOperation"=>$idOperation,
			"className"=>$classname,
			"dtfechorcargaIni"=>$dtfechorcargaIni,
			"dtfechorcargaFin"=>$dtfechorcargaFin,
			"lista"=>array(array(
						"ctipolote"=>"3",
						"cestatus"=>$cestatus,
						"acprefix"=>$idProductoS,
						"acnumlote"=>$acnumlote,
						"rifEmpresa"=>$idempresa,
						"exonerado"=>$exoboolean
				)),
			"nombreEmpresa"=>"",
			"acdir"=>"",
			"rif"=>"",
			"logAccesoObject"=>$logAcceso,
			"token"=>$token
			);

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);

		log_message('info','lista inno_en_proceso 2 data request=>'.$data);

		$dataEncry = np_Hoplite_Encryption($data, 'callWSListaInnominadasEnProc');
		$data = array('bean'=>$dataEncry, 'pais' =>$urlCountry );

		log_message('info','lista inno_en_proceso 2 data request=>'.json_encode($data,JSON_UNESCAPED_UNICODE));

		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSListaInnominadasEnProc');

		log_message('info','lista inno_en_proceso data response=>'.$jsonResponse);

		$response = json_decode($jsonResponse);

		if($response){
			if($response->rc==0){
				$response = json_decode($response->bean);
				return $response;
			}else{

				log_message('info','lista inno_en_proceso data response => '.json_encode($response,JSON_UNESCAPED_UNICODE));

				if($response->rc==-61 || $response->rc==-29){
					$this->session->sess_destroy();
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
			log_message('info','lista inno_en_proceso => ' + $response);
			return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
		}

	}

	public function callWSListaTarjetasInnominadas($urlCountry, $numLote){

		$this->lang->load('erroreseol');

		$token = $this->session->userdata('token');

		$idOperation = "getListadoTarjetasInnominadas";
		$classname = "com.novo.objects.MO.ListadoLotesMO";
		$canal = "ceo";
		$modulo = "getListadoTarjetasInnominadas";
		$funcion = "ListadoTarjetasInnominadas";
		$operation = "ListadoTarjetasInnominadas";

		$idempresa = $this->session->userdata('acrifS');
		$idProductoS = $this->session->userdata('idProductoS');

        $userName = $this->session->userdata('userName');
        $sessionId = $this->session->userdata('sessionId');
        $timeLog   = date("m/d/Y H:i");
        $ip= $this->input->ip_address();

		$sessionId = $this->session->userdata('sessionId');
		$logAcceso = np_hoplite_log($sessionId,$userName,$canal,$modulo,$funcion,$operation,0,$ip,$timeLog);

		$data = array(
			"pais"=>$urlCountry,
			"idOperation"=>$idOperation,
			"className"=>$classname,
			"tarjetasInnominadas"=>array(array(
						"numLote"=>$numLote,
						"idExtEmp"=>$idempresa,
						"estatus"=>"0",
						"enmascarar"=> true
			)),
			"idProducto" => $idProductoS,
			"logAccesoObject"=>$logAcceso,
			"token"=>$token
			);

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);

		//log_message('info','lista inno_en_proceso 1 data request=>'.$prueba);
		log_message('info','lista tarjetas_innominada data request=>'.$data);

		$dataEncry = np_Hoplite_Encryption($data, 'callWSListaTarjetasInnominadas');
		$data = array('bean'=>$dataEncry, 'pais' =>$urlCountry );

		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSListaTarjetasInnominadas');

		log_message('info','lista tarjetas_innominada data response=>'.$jsonResponse);

		$response = json_decode($jsonResponse);

		if($response){
			if($response->rc==0){
				$response = json_decode($response->bean);
				return $response;
			}else{
				return "";
			}

		}else{
			log_message('info','lista inno_en_proceso => ' + $response);
			return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
		}

	}

	public function callWSEliminarInnominadas($urlCountry, $pass, $idlote, $numlote){

		$this->lang->load('erroreseol');

		$token = $this->session->userdata('token');

		$idOperation = "eliminarLotesPorAutorizar";
		$classname = "com.novo.objects.MO.ListadoLotesMO";
		$canal = "ceo";
		$modulo = "eliminarLotesPorAutorizar";
		$funcion = "eliminarLotesPorAutorizar";
		$operation = "eliminarLotesPorAutorizar";

		$idempresa = $this->session->userdata('acrifS');
		$username = $this->session->userdata('userName');

        $userName = $this->session->userdata('userName');
        $sessionId = $this->session->userdata('sessionId');
        $timeLog   = date("m/d/Y H:i");
        $ip= $this->input->ip_address();

		$sessionId = $this->session->userdata('sessionId');
		$logAcceso = np_hoplite_log($sessionId,$userName,$canal,$modulo,$funcion,$operation,0,$ip,$timeLog);

		$data = array(
			"pais"=>$urlCountry,
			"idOperation"=>$idOperation,
			"className"=>$classname,
			"listaLotes"=>array( "lista"=>array(array(
						"acrif"=>$idempresa,
						"acidlote"=>$idlote,
						"acnumlote"=>$numlote,
						"ctipolote"=>"3"
			))),
			"usuario"=>array("userName"=>$username, "password"=>$pass),
			"logAccesoObject"=>$logAcceso,
			"token"=>$token
			);

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);

		//log_message('info','lista inno_en_proceso 1 data request=>'.$prueba);
		log_message('info','eliminar_lote data request=>'.$data);

		$dataEncry = np_Hoplite_Encryption($data, 'callWSEliminarInnominadas');
		$data = array('bean'=>$dataEncry, 'pais' =>$urlCountry );

		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSEliminarInnominadas');

		log_message('info','eliminar_lote data response=>'.$jsonResponse);

		$response = json_decode($jsonResponse);

		if($response){
			if($response->rc==0){
				return $response;
			}else{

				log_message('info','eliminar_lote data response => '.json_encode($response,JSON_UNESCAPED_UNICODE));

				if($response->rc==-61 || $response->rc==-29){
					$this->session->sess_destroy();
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
			log_message('info','eliminar_lote => ' + $response);
			return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
		}

	}
}
