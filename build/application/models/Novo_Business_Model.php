<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para obtener los datos del negocio
 * @author J. Enrique peñaloza Piñero
 * @date October 31st, 2019
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

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				$enterpriseArgs = $response->listadoEmpresas;
				$enterpriseArgs->sizePage = $sizePage;
				$enterpriseList = $this->requestdata->OrderEnterpriseList($enterpriseArgs, $filters, $dataRequest);
				$this->response->data->list = $enterpriseList->list;

				if(!$dataRequest) {
					$access = [
						'user_access',
						'getProducts'
					];

					$this->session->unset_userdata($access);

					$this->response->data->filters = $enterpriseList->filters;
					$this->response->data->enterprisesTotal = $response->listadoEmpresas->totalRegistros;
					$this->response->data->recordsPage = ceil($this->response->data->enterprisesTotal/$sizePage);
					$this->response->data->text = '';
				}
				break;
			case -6:
				$this->response->title = lang('ENTERPRISE_TITLE');
				$this->response->code = 1;
				$this->response->data->text = lang('ENTERPRISE_NOT_ASSIGNED');
			break;
			default:
				$this->response->title = lang('ENTERPRISE_TITLE');
				$this->response->data->text = lang('ENTERPRISE_NOT_OBTEIN');
				$this->response->data->resp['btn1']['link'] = base_url('cerrar-sesion');
		}

		if(!$dataRequest && $this->response->code != 0) {
			$this->response->data->filters = $filters;
			$this->response->data->enterprisesTotal = 0;
			$this->response->data->recordsPage = ceil($this->response->data->enterprisesTotal/$sizePage);
			$this->response->data->list = [];
		}

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

		$response = $this->sendToService(lang('GEN_GET_PRODUCTS'));
		$this->response->data->widget = $dataRequest;

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->session->set_userdata('getProducts', $dataRequest);
				$noDeleteCat = [];
				$noDeleteBrand = [];

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
							case 'idCategoria':
								$noDeleteCat[] =  $value;
								break;
							case 'filial':
								$products->$key = mb_strtoupper($value);
								break;
							case 'marca':
								$newValue = mb_strtolower($value).'.png';
								if(!file_exists(assetPath('images/brands/'.$newValue))) {
									$newValue = 'default.png';
								}
								$products->imgBrand = $newValue;
								$noDeleteBrand[] =  $value;
								break;
						}
					}
				}

				$noDeleteCat = array_unique($noDeleteCat);
				sort($noDeleteCat);
				$categorieList = [];

				foreach($response->listaCategorias AS $pos => $categorie) {
					foreach($noDeleteCat AS $item) {
						if($categorie->idCategoria == $item) {
							$categorieList[] = $response->listaCategorias[$pos];
						}
					}
				}

				$noDeleteCat = array_unique($noDeleteBrand);
				sort($noDeleteCat);
				$brandList = [];

				foreach($response->listaMarcas AS $pos => $brand) {
					foreach($noDeleteCat AS $item) {
						if(mb_strtolower($brand->nombre) == mb_strtolower($item)) {
							$brandList[] = $response->listaMarcas[$pos];
						}
					}
				}

				$this->response->data->categoriesList = $categorieList;
				$this->response->data->brandList = $brandList;
				$this->response->data->productList = $response->productos;
				break;
		}

		if($this->response->code != 0) {
			$this->response->data->categoriesList = [];
				$this->response->data->brandList = [];
				$this->response->data->productList = [];
		}


		return $this->responseToTheView(lang('GEN_GET_PRODUCTS'));
	}

}
