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
		$this->load->library('RequestData');
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

		$sizePage = $this->requestdata->setPageSize($this->session->screenSize);

		$this->dataRequest->idOperation = 'listaEmpresas';
		$this->dataRequest->accodusuario = $this->userName;
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->tamanoPagina = $sizePage;
		$this->dataRequest->filtroEmpresas = '';

		$response = $this->sendToService(lang('GEN_GET_ENTERPRISES'));
		$filters = $this->requestdata->setFilters();
		$responseList = new stdClass();

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				$enterpriseArgs = $response->listadoEmpresas;
				$enterpriseArgs->sizePage = $sizePage;
				$enterpriseList = $this->requestdata->OrderEnterpriseList($enterpriseArgs, $filters, $dataRequest);
				$responseList->list = $enterpriseList->list;

				if(!$dataRequest) {
					$responseList->filters = $enterpriseList->filters;
					$responseList->enterprisesTotal = $response->listadoEmpresas->totalRegistros;
					$responseList->recordsPage = ceil($responseList->enterprisesTotal/$sizePage);
				}

			break;
			case -6:
				$this->response->code = 1;
			break;
		}

		if(!$dataRequest && $this->response->code != 0) {
			$responseList->filters = $filters;
			$responseList->enterprisesTotal = 0;
			$responseList->recordsPage = ceil($responseList->enterprisesTotal/$sizePage);
			$responseList->list = [];
		}
		$this->response->data = $responseList;

		return $this->responseToTheView(lang('GEN_GET_ENTERPRISES'));
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
