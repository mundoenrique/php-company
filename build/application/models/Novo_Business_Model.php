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
	 */
	public function callWs_getEnterprises_Business($dataRequest = FALSE)
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
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->filtroEmpresas = '';

		$response = $this->sendToService('getEnterprises');
		$responseList = new stdClass();

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				$enterpriseList = $this->OrderEnterpriseList($response->listadoEmpresas->lista);
				$responseList->list = $enterpriseList->list;
				$responseList->filters = $enterpriseList->filters;
				$responseList->curretPage = trim($response->listadoEmpresas->paginaActual);
				$responseList->totalPages = trim($response->listadoEmpresas->totalPaginas);
				$responseList->enterprisesTotal = $response->listadoEmpresas->totalRegistros;
				$responseList->recordsPage = $this->dataRequest->tamanoPagina;

				$this->response->data = $responseList;
				log_message('DEBUG', 'NOVO ['.$this->userName.'] RESPONSE getEnterprises: '.json_encode($enterpriseList));
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para ordenar lista de empresas para vista consolidada
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 13th, 2019
	 */
	private function OrderEnterpriseList($enterpriseList)
	{
		$responseList = new stdClass();
		$filters = new stdClass();
		$filters->FIRST = [
			'active' => FALSE,
			'filter' => lang('ENTERPRISE_FILTER_1').'_1',
			'text' => lang('ENTERPRISE_FILTER_1'),
		];
		$filters->SECOND = [
			'active' => FALSE,
			'filter' => lang('ENTERPRISE_FILTER_2').'_1',
			'text' => lang('ENTERPRISE_FILTER_2')
		];
		$filters->THIRD = [
			'active' => FALSE,
			'filter' => lang('ENTERPRISE_FILTER_3').'_1',
			'text' => lang('ENTERPRISE_FILTER_3')
		];
		$filters->FOURTH = [
			'active' => FALSE,
			'filter' => lang('ENTERPRISE_FILTER_4').'_1',
			'text' => lang('ENTERPRISE_FILTER_4')
		];
		$filters->FIFTH = [
			'active' => FALSE,
			'filter' => lang('ENTERPRISE_FILTER_5').'_1',
			'text' => lang('ENTERPRISE_FILTER_5')
		];
		$filters->SIXTH = [
			'active' => FALSE,
			'filter' => lang('ENTERPRISE_FILTER_6').'_1',
			'text' => lang('ENTERPRISE_FILTER_6')
		];
		$filters->SEVENTH = [
			'active' => FALSE,
			'filter' => lang('ENTERPRISE_FILTER_7').'_1',
			'text' => lang('ENTERPRISE_FILTER_7')
		];

		$item = 1; $page = 1; $cat = FALSE;
		$itemAlphaBeFi = 1; $itemAlphaBeSec = 1; $itemAlphaBeTh = 1;  $itemAlphaBeFo = 1; $itemAlphaBeFif = 1;
		$itemAlphaBeSi = 1; $itemAlphaBeSev = 1;
		$pageAlphaBeFi = 1; $pageAlphaBeSec = 1; $pageAlphaBeTh = 1;  $pageAlphaBeFo = 1; $pageAlphaBeFif = 1;
		$pageAlphaBeSi = 1; $pageAlphaBeSev = 1;
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
							if($itemAlphaBeFi > $this->dataRequest->tamanoPagina) {
								$itemAlphaBeFi = 1; 	$pageAlphaBeFi++;
							}
							$enterpriseList[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_1').'_'.$pageAlphaBeFi;
							$itemAlphaBeFi++;
							if(!$filters->FIRST['active']) {
								$filters->FIRST['active'] = TRUE;
							}
							break;
						case strpos('DEFG', $cat) !== FALSE:
							if($itemAlphaBeSec > $this->dataRequest->tamanoPagina) {
								$itemAlphaBeSec = 1; 	$pageAlphaBeSec++;
							}
							$enterpriseList[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_2').'_'.$pageAlphaBeSec;
							$itemAlphaBeSec++;
							if(!$filters->SECOND['active']) {
								$filters->SECOND['active'] = TRUE;
							}
							break;
						case strpos('HIJK', $cat) !== FALSE:
							if($itemAlphaBeTh > $this->dataRequest->tamanoPagina) {
								$itemAlphaBeTh = 1; 	$pageAlphaBeTh++;
							}
							$enterpriseList[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_3').'_'.$pageAlphaBeTh;
							$itemAlphaBeTh++;
							if(!$filters->THIRD['active']) {
								$filters->THIRD['active'] = TRUE;
							}
							break;
						case strpos('LMNO', $cat) !== FALSE:
							if($itemAlphaBeFo > $this->dataRequest->tamanoPagina) {
								$itemAlphaBeFo = 1; 	$pageAlphaBeFo++;
							}
							$enterpriseList[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_4').'_'.$pageAlphaBeFo;
							$itemAlphaBeFo++;
							if(!$filters->FOURTH['active']) {
								$filters->FOURTH['active'] = TRUE;
							}
							break;
						case strpos('PQRS', $cat) !== FALSE:
							if($itemAlphaBeFi > $this->dataRequest->tamanoPagina) {
								$itemAlphaBeFi = 1; 	$pageAlphaBeFif++;
							}
							$enterpriseList[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_5').'_'.$pageAlphaBeFif;
							$itemAlphaBeFif++;
							if(!$filters->FIFTH['active']) {
								$filters->FIFTH['active'] = TRUE;
							}
							break;
						case strpos('TUVW', $cat) !== FALSE:
							if($itemAlphaBeSi > $this->dataRequest->tamanoPagina) {
								$itemAlphaBeSi = 1; 	$pageAlphaBeSi++;
							}
							$enterpriseList[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_6').'_'.$pageAlphaBeSi;
							$itemAlphaBeSi++;
							if(!$filters->SIXTH['active']) {
								$filters->SIXTH['active'] = TRUE;
							}
							break;
						case strpos('XYZ', $cat) !== FALSE:
							if($itemAlphaBeSev > $this->dataRequest->tamanoPagina) {
								$itemAlphaBeSev = 1; 	$pageAlphaBeSev++;
							}
							$enterpriseList[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_7').'_'.$pageAlphaBeSev;
							$itemAlphaBeSev++;
							if(!$filters->SEVENTH['active']) {
								$filters->SEVENTH['active'] = TRUE;
							}
							break;
					}
				}

			}
			$item++;
		}

		$responseList->list = $enterpriseList;
		$responseList->filters = $filters;

		return $responseList;
	}
	/**
	 * @info Método para obtener lista d eproductos para una empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 13th, 2019
	 */
	public function callWs_getProducts_Business($dataRequest)
	{
		log_message('INFO', 'NOVO Business Model: getProducts method Initialized');
		$menu = [
			'user_access'
		];
		$this->session->unset_userdata($menu);
		$this->className = "com.novo.objects.TOs.UsuarioTO";

		$this->dataAccessLog->modulo = 'Negocios';
		$this->dataAccessLog->function = 'Productos';
		$this->dataAccessLog->operation = 'lista de productos';

		$this->dataRequest->idOperation = 'menuEmpresa';

		$this->dataRequest->ctipo = isset($dataRequest->type) ? $dataRequest->type : 'A';
		$this->dataRequest->userName = $this->userName;
		$this->dataRequest->idEmpresa = $dataRequest->idFiscal;

		$response = $this->sendToService('getProducts');

		switch($this->isResponseRc) {
			case 0:
				log_message('DEBUG', 'NOVO ['.$this->userName.'] RESPONSE getProducts: '.json_encode($response));
				$this->session->set_userdata('getProducts', $dataRequest);

				$responseList = new stdClass();
				$responseList->widget = $dataRequest;
				$this->response->code = 0;
				$this->response->data = $responseList;
				break;

		}

		return $this->response;
	}

}
