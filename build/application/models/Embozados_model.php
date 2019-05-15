<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Embozados_model extends CI_Model {

	protected $pais;
	protected $canal;
	protected $timeLog;
	protected $ip;
	protected $rc = 0;
	protected $sessionId;
	protected $userName;
	protected $token;

	public function __construct()
	{
		log_message('INFO', 'NOVO Plantilla Model Class Initialized');
	}

	public function cambioStatus($nlote,$tipoStatus='TIPO_B'){
		$this->lang->load('erroreseol');
		$this->lang->load('dashboard');

				$operation="estatusLotes";
				$className="com.novo.objects.MO.EstatusLotesMO";

				$username = $this->session->userdata('userName');

        $sessionId = $this->session->userdata('sessionId');
        $timeLog   = date("m/d/Y H:i");
        $ip= $this->input->ip_address();
				$canal = "ceo";
				$modulo="statusLotesOS";
				$function="tipoStatusLotesOS";


        $logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

        $token = $this->session->userdata('token');
				$this->pais = $this->session->userdata('pais');
        $data = array(
						'nlote' => $nlote,
						'tipoEstatus' => $tipoStatus,
            'idOperation' => $operation,
            'className' => $className,
            'logAccesoObject' => $logAcceso,
						'token' => $token,
						"pais" => $this->pais
        );

        $data = json_encode($data,JSON_UNESCAPED_UNICODE);

        $dataEncry = np_Hoplite_Encryption($data, 'cambioStatus');
        $data = array('bean' => $dataEncry, 'pais' =>$this->pais );
        $data = json_encode($data);
        $response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
				$jsonResponse = np_Hoplite_Decrypt($response, 'cambioStatus');

				$jsonResponse = json_decode($jsonResponse);

		if(isset($jsonResponse->rc)) {
			$data = '';

			switch($jsonResponse->rc) {

				case 0:
					$code = 0;
					$title = 'Notificación';
					$msg = 'SU notificación fue recibida exitosamente';
					break;
				case -29:
				case -61:
					$code = 3;
					$title = lang('SYSTEM_NAME');
					$msg = lang('ERROR_(-29)');
					break;
				default:
					$code = 2;
					$title = lang('SYSTEM_NAME');
					$msg = lang('ERROR_('.$jsonResponse->rc.')');
				}
		} else {
			$code = 2;
			$title = lang('SYSTEM_NAME');
			$msg = lang('ERROR_GENERICO_USER');
			}

		if($code === 3) {
			$this->session->sess_destroy();
		}

		$response = [
			'code' => $code,
			'title' => $title,
			'msg' => isset($msg) ? $msg : '',
			'data' => isset($data) ? $data : ''
		];
		return $response;

	}
}
