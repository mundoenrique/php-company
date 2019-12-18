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
		log_message('INFO', 'NOVO Business Model Class Initialized');

		parent:: __construct();
		$this->load->library('Request_Data');
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

		$sizePage = $this->request_data->setPageSize($this->session->screenSize);
		$this->dataRequest->idOperation = 'listaEmpresas';
		$this->dataRequest->accodusuario = $this->userName;
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->tamanoPagina = $sizePage;
		$this->dataRequest->filtroEmpresas = '';

		$response = $this->sendToService(lang('GEN_GET_ENTERPRISES'));
		$filters = FALSE;

		if(!$dataRequest) {
			$filters = $this->request_data->setFilters();
		}

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$enterpriseArgs = $response->listadoEmpresas;
				$enterpriseArgs->sizePage = $sizePage;
				$enterpriseList = $this->request_data->OrderEnterpriseList($enterpriseArgs, $filters, $dataRequest);
				$this->response->data->list = $enterpriseList->list;
				if(!$dataRequest) {
					$access = [
						'user_access',
						'productInf',
						'enterpriseInf'
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
				$this->response->data->text = lang('GEN_ENTERPRISE_NOT_OBTEIN');
				$this->response->data->resp['btn1']['link'] = base_url('cerrar-sesion');
		}

		if($this->response->code != 0) {

			if(!$dataRequest)	{
				$this->response->data->filters = $filters;
				$this->response->data->enterprisesTotal = 0;
				$this->response->data->recordsPage = ceil($this->response->data->enterprisesTotal/$sizePage);
			}

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

		$select = isset($dataRequest->select);
		if(!$select) {
			$access = [
				'user_access',
				'productInf',
				'enterpriseInf'
			];
			$this->session->unset_userdata($access);
			unset($dataRequest->select);
		}

		$this->className = "com.novo.objects.TOs.UsuarioTO";
		$this->dataAccessLog->modulo = 'Negocios';
		$this->dataAccessLog->function = 'Productos';
		$this->dataAccessLog->operation = 'lista de productos';

		$this->dataRequest->idOperation = 'menuEmpresa';
		$this->dataRequest->ctipo = isset($dataRequest->type) ? $dataRequest->type : 'A';
		$this->dataRequest->userName = $this->userName;
		$this->dataRequest->idEmpresa = $dataRequest->idFiscal;

		$response = $this->sendToService(lang('GEN_GET_PRODUCTS'));

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$select ?: $this->session->set_userdata('enterpriseInf', $dataRequest);
				$productList = $this->request_data->getProductsOrder($response, $select);
				$this->session->unset_userdata('products');
				count($response->productos) < 2 ?: $this->session->set_userdata('products', TRUE);

				if($select) {
					$this->response->data = $productList;
				} else {
					$this->response->data->categoriesList = $productList->categorieList;
					$this->response->data->brandList = $productList->brandList;
					$this->response->data->productList = $productList->productList;
				}
				break;
			case -138:
				$this->response->code = 3;
				$this->response->msg = 'No fue posible obtener la lista de productos asociados, vuelve a intentarlor';
				break;
		}

		if($this->response->code != 0 && !$select) {
			$this->response->data->categoriesList = [];
			$this->response->data->brandList = [];
			$this->response->data->productList = [];
		}

		return $this->responseToTheView(lang('GEN_GET_PRODUCTS'));
	}
	/**
	 * @info Método para obtener lista de productos para una empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 12th, 2019
	 */
	public function callWs_getProductDetail_Business($dataRequest)
	{
		log_message('INFO', 'NOVO Business Model: getProductDetail method Initialized');

		$this->className = "com.novo.objects.MO.ListadoMenuMO";
		$this->dataAccessLog->modulo = 'Negocios';
		$this->dataAccessLog->function = 'Producto';
		$this->dataAccessLog->operation = 'Detalle Producto';
		$enterpriseInf = $this->session->enterpriseInf;
		$productPrefix = $dataRequest->productPrefix;

		if(isset($dataRequest->goToDetail)) {
			unset($dataRequest->goToDetail, $dataRequest->productPrefix);
			$enterpriseInf = $dataRequest;
			$this->session->set_userdata('enterpriseInf', $dataRequest);
		}

		$this->dataRequest->idOperation = 'menuPorProducto';
		$this->dataRequest->menus = [
			[
				'app' => 'EOL',
				'prod' => $productPrefix,
				'idUsuario' => $this->userName,
				'idEmpresa' => $enterpriseInf->idFiscal,
			]
		];
		$this->dataRequest->estadistica = [
			'producto' => [
				'prefijo' => $productPrefix,
				'rifEmpresa' => $enterpriseInf->idFiscal,
				'acCodCia' => $enterpriseInf->enterpriseCode,
				'acCodGrupo' => $enterpriseInf->enterpriseGroup
			]
		];

		$response = $this->sendToService(lang('GEN_GET_PRODUCTS_DETAIL'));
		$productDetail = [
			'name' => '--',
			'imgProgram' => 'default.svg',
			'brand' => '',
			'imgBrand' => 'default.png',
			'viewSomeAttr' => TRUE,
			'prefix' => $productPrefix
		];
		$productSummary = [
			'lots' => '--',
			'toSign' => '--',
			'toAuthorize' => '--',
			'serviceOrders' => '--',
			'serviceOrdersCon' => '--',
			'serviceOrdersNoCon' => '--',
			'totalCards' => '--',
			'activeCards' => '--',
			'inactiveCards' => '--'
		];

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				log_message('INFO', 'NOVO ['.$this->userName.'] '.lang('GEN_GET_PRODUCTS_DETAIL').' USER_ACCESS LIST: '.json_encode($response->lista));

				if(isset($response->estadistica->producto->idProducto)) {
					$imgBrand = url_title(trim(mb_strtolower($response->estadistica->producto->marca))).'_card.svg';

					if(!file_exists(assetPath('images/brands/'.$imgBrand))) {
						$imgBrand = 'default.png';
					}

					$imgProgram = url_title(trim(mb_strtolower($response->estadistica->producto->nombre))).'.svg';

					if(!file_exists(assetPath('images/programs/'.$imgProgram))) {
						$imgProgram = 'default.svg';
					}

					$productName = ucwords(mb_strtolower($response->estadistica->producto->descripcion));
					$productDetail['name'] = $productName;
					$productDetail['imgProgram'] = $imgProgram;
					$brand = trim($response->estadistica->producto->marca);
					$productDetail['brand'] = $brand;
					$productDetail['imgBrand'] = $imgBrand;

					if(trim($response->estadistica->producto->idProducto) == 'G') {
						$productDetail['viewSomeAttr'] = FALSE;
					}

					$productInf = new stdClass();
					$productInf->productPrefix = $productPrefix;
					$productInf->productName = $productName;
					$productInf->brand = $brand;
					$sess = [
						'productInf' => $productInf,
						'user_access' => $response->lista
					];
					$this->session->set_userdata($sess);
				} else {
					$this->response->code = 3;
					$this->response->title = lang('PRODUCTS_DETAIL_TITLE');
					$this->response->msg = lang('RESP_UNCONFIGURED_PRODUCT');
				}

				$productSummary['lots'] = trim($response->estadistica->lote->total);
				$productSummary['toSign'] = trim($response->estadistica->lote->numPorFirmar);
				$productSummary['toAuthorize'] = trim($response->estadistica->lote->numPorAutorizar);


				if(isset($response->estadistica->ordenServicio)) {
					$productSummary['serviceOrders'] = trim($response->estadistica->ordenServicio->Total);
					$productSummary['serviceOrdersCon'] = trim($response->estadistica->ordenServicio->numConciliada);
					$productSummary['serviceOrdersNoCon'] = trim($response->estadistica->ordenServicio->numNoConciliada);
					$productSummary['totalCards'] = trim($response->estadistica->listadoTarjeta->numeroTarjetas);
				}

				if(isset($response->estadistica->ordenServicio)) {
					$productSummary['activeCards'] = trim($response->estadistica->listadoTarjeta->numTarjetasActivas);
					$productSummary['inactiveCards'] = trim($response->estadistica->listadoTarjeta->numTarjetasInactivas);
				}

				if(isset($response->estadistica->producto->mesesVencimiento)) {
					$expMaxMonths = trim($response->estadistica->producto->mesesVencimiento);
					$currentDate = date('Y-m');
					$newDate = strtotime ('+'.$expMaxMonths.' month' , strtotime($currentDate));
					$expireDate = date ('m/Y' , $newDate);
					$expMax = new stdClass();
					$expMax->expMaxMonths = $expireDate;
					$expMax->maxCards = trim($response->estadistica->producto->maxTarjetas);
					$this->session->set_userdata('expMax', $expMax);
				}
				break;
			case -99:
				$this->response->code = 3;
				$this->response->msg = novoLang(lang('RESP_NO_ACCESS'), $this->userName);
				break;
		}

		$this->response->data->productDetail = (object) $productDetail;
		$this->response->data->productSummary = (object) $productSummary;

		return $this->responseToTheView(lang('GEN_GET_PRODUCTS_DETAIL'));
	}
}
