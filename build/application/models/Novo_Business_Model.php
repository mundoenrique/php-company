<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para
 * @author
 *
 */
class Novo_Business_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Business Model Class Initialized');
	}
	/**
	 * @info envia la peticion al servidor API para obtener la lista de las empresas
	 * @author Pedro Torres
	 * @date 23/08/2019
	 *
	 */
	public function callWs_getEnterprises_Business()
	{
		log_message('INFO', 'NOVO Business Model: Enterprises method Initialized');
		$menu = [
			'user_access'
		];
		$this->session->unset_userdata($menu);
		$this->className = "com.novo.objects.MO.ListadoEmpresasMO";

		$this->dataAccessLog->modulo = 'dashboard';
		$this->dataAccessLog->function = 'dashboard';
		$this->dataAccessLog->operation = 'listaEmpresas';

		$this->dataRequest->accodusuario = $this->session->userdata('userName');
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->tamanoPagina = 9;
		$this->dataRequest->filtroEmpresas = '';

		log_message('DEBUG', 'NOVO ['.$this->session->userdata('userName').'] RESPONSE: Business: ' . json_encode($this->response));
		$this->response = $this->sendToService('Business');

		switch($this->isResponseRc) {
			case -5000:
				$this->response->code = 1;
				$this->response->title = lang('GETENTERPRISES_TITLE-'.$this->isResponseRc);
				$this->response->className = 'error-login-2';
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				break;
			case -6000:
				$this->response->code = 3;
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				$this->response->icon = 'ui-icon-info';
				break;
		}
		return $this->response;
	}

	public function callWs_getProducts_Business($params)
	{
		log_message('INFO', 'NOVO Business Model: Enterprises method Initialized');
		$menu = [
			'user_access'
		];
		$this->session->unset_userdata($menu);
		$this->className = "com.novo.objects.TOs.UsuarioTO";

		$this->dataAccessLog->modulo = 'dashboard';
		$this->dataAccessLog->function = 'dashboard';
		$this->dataAccessLog->operation = 'menuEmpresa';

		$this->dataRequest->userName = $this->session->userdata('userName');
		$this->dataRequest->ctipo = "A";
		$this->dataRequest->idEmpresa = $params['acrifS'];

		log_message('DEBUG', 'NOVO ['.$this->session->userdata('userName').'] RESPONSE: Business: ' . json_encode($this->dataRequest));
		$this->response = $this->sendToService('Business');

		switch($this->isResponseRc) {
			case -5000:
				$this->response->code = 1;
				$this->response->title = lang('GETENTERPRISES_TITLE-'.$this->isResponseRc);
				$this->response->className = 'error-login-2';
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				break;
			case -6000:
				$this->response->code = 3;
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				$this->response->icon = 'ui-icon-info';
				break;
		}
		return $this->response;
	}

	public function callWs_listEnterprises_Business()
	{
		log_message('INFO', 'NOVO Business Model: Enterprises method Initialized');
		$menu = [
			'user_access'
		];
		$this->session->unset_userdata($menu);
		$this->className = "com.novo.objects.MO.ListadoEmpresasMO";

		$this->dataAccessLog->modulo = 'dashboard';
		$this->dataAccessLog->function = 'dashboard';
		$this->dataAccessLog->operation = 'getPaginar';

		$this->dataRequest->accodusuario = $this->session->userdata('userName');
		$this->dataRequest->paginaActual = NULL;
		$this->dataRequest->tamanoPagina = NULL;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->filtroEmpresas = NULL;

		log_message('DEBUG', 'NOVO ['.$this->session->userdata('userName').'] RESPONSE: Business: ' . json_encode($this->dataRequest));
		$this->response = $this->sendToService('Business');

		switch($this->isResponseRc) {
			case -5000:
				$this->response->code = 1;
				$this->response->title = lang('GETENTERPRISES_TITLE-'.$this->isResponseRc);
				$this->response->className = 'error-login-2';
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				break;
			case -6000:
				$this->response->code = 3;
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				$this->response->icon = 'ui-icon-info';
				break;
		}
		return $this->response;
	}
}
