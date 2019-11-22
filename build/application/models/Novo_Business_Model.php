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
	public function callWs_getEnterprises_Business($dataRequest)
	{
		log_message('INFO', 'NOVO Business Model: getEnterprises method Initialized');

		$this->session->unset_userdata('user_access');
		$this->className = "com.novo.objects.MO.ListadoEmpresasMO";

		$this->dataAccessLog->modulo = 'Negocios';
		$this->dataAccessLog->function = 'Empresas';
		$this->dataAccessLog->operation = 'lista de empresas';

		$screenSize = $this->session->userdata('screenSize');

		switch ($screenSize) {
			case $screenSize >= 1920:
				$sizePage = 12;
				break;
			case $screenSize >= 1440:
				$sizePage = 10;
				break;
			case $screenSize >= 1200:
				$sizePage = 8;
				break;
			case $screenSize >= 992:
				$sizePage = 6;
			break;
			default:
				$sizePage = 4;
		}

		$this->dataRequest->idOperation = 'listaEmpresas';//!$dataRequest ? 'listaEmpresas' : 'getPaginar';
		$this->dataRequest->accodusuario = $this->session->userdata('userName');
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->tamanoPagina = $sizePage;
		$this->dataRequest->filtroEmpresas = '';
		$response = $this->sendToService(lang('GEN_GET_ENTERPRISES'));
		$responseList = new stdClass();

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				$enterpriseList = $response->listadoEmpresas->lista;
				$enterpriseList = $this->OrderEnterpriseList($enterpriseList, $dataRequest);
				$responseList->list = $enterpriseList->list;
				if(!$dataRequest) {
					$responseList->filters = $enterpriseList->filters;
					$responseList->curretPage = trim($response->listadoEmpresas->paginaActual);
					$responseList->totalPages = trim($response->listadoEmpresas->totalPaginas);
					$responseList->enterprisesTotal = $response->listadoEmpresas->totalRegistros;
					$responseList->recordsPage = $this->dataRequest->tamanoPagina;
				}

				$this->response->data = $responseList;
				log_message('DEBUG', 'NOVO ['.$this->userName.'] RESPONSE getEnterprises: '.json_encode($enterpriseList));
				break;
			case 6:
				$this->response->code = 3;
				break;
		}

		return $this->responseToTheView(lang('GEN_GET_ENTERPRISES'));
	}
	/**
	 * @info Método para ordenar lista de empresas para vista consolidada
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 13th, 2019
	 */
	private function OrderEnterpriseList($enterpriseList, $dataRequest)
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
		$itemAlphaBeFi = 1; $itemAlphaBeSec = 1; $itemAlphaBeTh = 1;  $itemAlphaBeFo = 1;
		$itemAlphaBeFif = 1; $itemAlphaBeSi = 1; $itemAlphaBeSev = 1;
		$pageAlphaBeFi = 1; $pageAlphaBeSec = 1; $pageAlphaBeTh = 1;  $pageAlphaBeFo = 1;
		$pageAlphaBeFif = 1; $pageAlphaBeSi = 1; $pageAlphaBeSev = 1;
		$delete =  [];
		foreach($enterpriseList AS $pos => $enterprises) {
			foreach($enterprises AS $key => $value) {
				$enterpriseList[$pos]->$key = trim($value);
				if($dataRequest) {
					if($key === 'resumenProductos' && $value == 0) {
						$delete[] = $pos;
					}
					continue;
				}
				if($item > $this->dataRequest->tamanoPagina) {
					$item = 1;
					$page++;
				}

				$enterpriseList[$pos]->page = 'page_'.$page;

				if($key === 'resumenProductos') {
					$enterpriseList[$pos]->resumenProductos = $value == 1 ?
					$value.' '.lang('GEN_PRODUCT') :
					$value.' '.lang('GEN_PRODUCTS');
				}

				if($key === 'acpercontac') {
					$enterpriseList[$pos]->acpercontac = ucwords(mb_strtolower($value));
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

		if($dataRequest) {
			foreach($delete AS $pos) {
				unset($enterpriseList[$pos]);
			}
		}

		$responseList->list = $enterpriseList;

		if(!$dataRequest) {
			$responseList->filters = $filters;
		}

		return $responseList;
	}
	/**
	 * @info Método para obtener lista de productos para una empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 12th, 2019
	 */
	public function callWs_getProducts_Business($dataRequest)
	{
		log_message('INFO', 'NOVO Business Model: getProducts method Initialized');

		$this->session->unset_userdata('user_access');
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
				$this->session->set_userdata('getProducts', $dataRequest);

				$responseList = new stdClass();
				$responseList->widget = $dataRequest;
				$responseList->productList = $response;
				$this->response->code = 0;
				foreach($response->productos AS $pos => $products) {
					foreach($products AS $key => $value) {
						switch ($key) {
							case 'nombre':
								$value = url_title(mb_strtolower($value)).'.svg';
								if(!file_exists(assetPath('images/programs/'.$value))) {
									$value = 'default.svg';
								}
								$products->programImg = $value;
							break;
							case 'descripcion':
								$products->$key = mb_strtoupper($value);
								break;
								case 'categoria':
								$products->$key = ucwords(mb_strtolower($value));
							break;
							case 'filial':
								$products->$key = mb_strtoupper($value);
								break;
							case 'marca':
								$value = mb_strtolower($value).'.png';
								if(!file_exists(assetPath('images/brands/'.$value))) {
									$value = 'default.png';
								}
								$products->imgBrand = $value;
								break;
							}
						}

					}
					$this->response->data = $responseList;
					log_message('INFO', 'NOVO ['.$this->userName.'] RESPONSE getProducts: '.json_encode($response));
				break;

			}

			return $this->response;
		}

	}
