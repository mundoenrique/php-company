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
	 * @info Obtiene la lista de empresas para un usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 1st, 2019
	 *
	 */
	public function callWs_getEnterprises_Business($select = FALSE)
	{
		log_message('INFO', 'NOVO Business Model: getEnterprises method Initialized');

		$menu = [
			'user_access'
		];
		$this->session->unset_userdata($menu);
		$this->className = "com.novo.objects.MO.ListadoEmpresasMO";

		$this->dataAccessLog->modulo = 'Negocios';
		$this->dataAccessLog->function = 'Empresas';
		$this->dataAccessLog->operation = 'lista de empresas';

		$this->dataRequest->idOperation = 'listaEmpresas';
		$this->dataRequest->accodusuario = $this->session->userdata('userName');
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->tamanoPagina = 8;
		$this->dataRequest->filtroEmpresas = '';

		$response = $this->sendToService('getEnterprises');

		switch($this->isResponseRc) {
			case 0:
				$enterpriseList = json_encode($response->listadoEmpresas->lista);
				$enterpriseList = $response->listadoEmpresas->lista;
				$item = 1; $page = 1; $cat = FALSE;
				$itemAlphaBeA = 1; $itemAlphaBeD = 1; $itemAlphaBeH = 1;  $itemAlphaBeL = 1; $itemAlphaBeP = 1;
				$itemAlphaBeT = 1; $itemAlphaBeX = 1;
				$pageAlphaBeA = 1; $pageAlphaBeD = 1; $pageAlphaBeH = 1;  $pageAlphaBeL = 1; $pageAlphaBeP = 1;
				$pageAlphaBeT = 1; $pageAlphaBeX = 1;
				foreach($enterpriseList AS $pos => $enterprises) {
					foreach($enterprises AS $key => $value) {
						$enterpriseList[$pos]->$key = trim($value);

						if($item > $this->dataRequest->tamanoPagina) {
							$item = 1;
							$page++;
						}

						$enterpriseList[$pos]->page = 'page_'.$page;

						if($key === 'resumenProductos') {
							$enterpriseList[$pos]->resumenProductos = $enterpriseList[$pos]->resumenProductos == 1 ?
							$enterpriseList[$pos]->resumenProductos.' '.lang('GEN_PRODUCT') :
							$enterpriseList[$pos]->resumenProductos.' '.lang('GEN_PRODUCTS');
						}

						if($key === 'acpercontac') {
							$enterpriseList[$pos]->acpercontac = ucwords(mb_strtolower($enterpriseList[$pos]->acpercontac));
						}

						if($key === 'acnomcia') {
							$cat = substr($enterpriseList[$pos]->$key, 0, 1);
							$enterpriseList[$pos]->category = $cat;

							switch ($cat) {
								case strpos('ABC', $cat) !== FALSE:
									if($itemAlphaBeA > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeA = 1; 	$pageAlphaBeA++;
									}
									$enterpriseList[$pos]->albeticalPage = 'A-C_'.$pageAlphaBeA;
									$itemAlphaBeA++;
									break;
								case strpos('DEFG', $cat) !== FALSE:
									if($itemAlphaBeD > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeD = 1; 	$pageAlphaBeD++;
									}
									$enterpriseList[$pos]->albeticalPage = 'D-G_'.$pageAlphaBeD;
									$itemAlphaBeD++;
									break;
								case strpos('HIJK', $cat) !== FALSE:
									if($itemAlphaBeH > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeH = 1; 	$pageAlphaBeH++;
									}

									$enterpriseList[$pos]->albeticalPage = 'H-K_'.$pageAlphaBeH;
									$itemAlphaBeH++;
									break;
								case strpos('LMNO', $cat) !== FALSE:
									if($itemAlphaBeL > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeL = 1; 	$pageAlphaBeL++;
									}
									$enterpriseList[$pos]->albeticalPage = 'L-O_'.$pageAlphaBeL;
									$itemAlphaBeL++;
									break;
								case strpos('PQRS', $cat) !== FALSE:
									if($itemAlphaBeP > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeP = 1; 	$pageAlphaBeP++;
									}
									$enterpriseList[$pos]->albeticalPage = 'P-S_'.$pageAlphaBeP;
									$itemAlphaBeP++;
									break;
								case strpos('TUVW', $cat) !== FALSE:
									if($itemAlphaBeT > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeT = 1; 	$pageAlphaBeT++;
									}
									$enterpriseList[$pos]->albeticalPage = 'T-W_'.$pageAlphaBeT;
									$itemAlphaBeT++;
									break;
								case strpos('XYZ', $cat) !== FALSE:
									if($itemAlphaBeX > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeX = 1; 	$pageAlphaBeX++;
									}
									$enterpriseList[$pos]->albeticalPage = 'X-Z_'.$pageAlphaBeX;
									$itemAlphaBeX++;
									break;
							}
						}

					}
					$item++;
				}
		}

			log_message('DEBUG', 'NOVO ['.$this->userName.'] RESPONSE getEnterprises: '.json_encode($enterpriseList));

			$responseList = new stdClass();
			$responseList->list = $enterpriseList;
			$responseList->curretPage = trim($response->listadoEmpresas->paginaActual);
			$responseList->totalPages = trim($response->listadoEmpresas->totalPaginas);
			$responseList->enterprisesTotal = $response->listadoEmpresas->totalRegistros;
			$responseList->recordsPage = $this->dataRequest->tamanoPagina;


			$this->response->code = 0;
			$this->response->data = $responseList;



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
