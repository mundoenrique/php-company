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
		writeLog('INFO', 'Business Model Class Initialized');

		$this->load->library('Request_Data');
	}
	/**
	 * @info Obtiene la lista de empresas para un usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 1st, 2019
	 */
	public function callWs_GetEnterprises_Business($dataRequest)
	{
		writeLog('INFO', 'Business Model: getEnterprises Method Initialized');

		$this->dataAccessLog->modulo = 'Negocios';
		$this->dataAccessLog->function = 'Empresas';
		$this->dataAccessLog->operation = 'lista de empresas';

		$sizePage = $this->request_data->setPageSize($this->session->screenSize);
		$this->dataRequest->idOperation = 'listaEmpresas';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoEmpresasMO';
		$this->dataRequest->accodusuario = $this->userName;
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->tamanoPagina = $sizePage;
		$this->dataRequest->filtroEmpresas = '';

		$response = $this->sendToWebServices('callWs_GetEnterprises');
		$filters = $this->request_data->setFilters();

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
					$this->response->data->text = $this->response->data->enterprisesTotal > 0 ? '' : 'Usuario sin empresas asignadas';
				}

				if (isset($enterpriseArgs->listaCuentasCBP_BDB) || isset($enterpriseArgs->listaCuentasICBS_BDB)) {
					$thirdEnterprise = new stdClass();
					$thirdEnterprise->cbpAccounts = $enterpriseArgs->listaCuentasCBP_BDB;
					$thirdEnterprise->icbsAccounts = $enterpriseArgs->listaCuentasICBS_BDB;

					$this->session->set_userdata('thirdEnterprise', $thirdEnterprise);
				}
				break;
			case -6:
				$this->response->code = 1;
				$this->response->title = lang('BUSINESS_ENTERPRISE_TITLE');
				$this->response->data->text = lang('BUSINESS_ENTERPRISE_NOT_ASSIGNED');
			break;
			case -430:
			case -431:
				$this->session->set_flashdata('unauthorized', lang('GEN_SINGLE_SIGNON'));
				redirect(base_url(lang('SETT_LINK_SIGNOUT').lang('SETT_LINK_SIGNOUT_END')), 'Localtion', 302);
				exit;
			break;
			case -432:
			case -433:
				$this->session->set_flashdata('unauthorized', lang('GEN_NO_PERMISSIONS'));
				redirect(base_url(lang('SETT_LINK_SIGNOUT').lang('SETT_LINK_SIGNOUT_END')), 'Localtion', 302);
				exit;
			break;
			case -434:
			case -435:
				$this->session->set_flashdata('unauthorized', lang('BUSINESS_ENTERPRISE_NOT_ASSIGNED'));
				redirect(base_url(lang('SETT_LINK_SIGNOUT').lang('SETT_LINK_SIGNOUT_END')), 'Localtion', 302);
				exit;
			break;
			default:
				$this->response->data->text = lang('GEN_ENTERPRISE_NOT_OBTEIN');

				if ($this->isResponseRc =! -29 || $this->isResponseRc =! -61) {
					clearSessionsVars();
				}
		}

		if($this->response->code != 0) {

			if(!$dataRequest)	{
				$this->response->data->filters = $filters;
				$this->response->data->enterprisesTotal = 0;
				$this->response->data->recordsPage = ceil($this->response->data->enterprisesTotal/$sizePage);
			}

			$this->response->data->list = [];
		}

		return $this->responseToTheView('callWs_GetEnterprises');
	}
	/**
	 * @info obtiene lista de sucursales
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 19th, 2019
	 */
	public function callWs_GetBranchOffices_Bulk($dataRequest)
	{
		writeLog('INFO', 'Bulk Model: GetBranchOffices Method Initialized');

		$this->dataAccessLog->modulo = 'Lotes';
		$this->dataAccessLog->function = 'Carga de lotes';
		$this->dataAccessLog->operation = 'Obtener sucursales';

		$select = isset($dataRequest->select);
		unset($dataRequest->select);
		$this->dataRequest = new stdClass();
		$this->dataRequest->idOperation = 'getConsultarSucursales';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoSucursalesMO';
		$this->dataRequest->paginaActual = '1';
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->lista = [
			[
				'rif' => $this->session->enterpriseInf->idFiscal,
				'acCodCia' => $this->session->enterpriseInf->enterpriseCode
			]
		];
		$newGet = isset($dataRequest->newGet) ? $dataRequest->newGet : 0;

		if ($newGet == 0) {
			$response = $this->sendToService('callWs_GetBranchOffices');
		} else {
			$dataRequest->rc = $dataRequest->newGet;
			$this->makeAnswer($dataRequest, 'callWs_GetBranchOffices');
		}

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				if($select && count($response->lista) > 1) {
					$branchOffice[] = (object) [
						'key' => '',
						'text' => lang('BULK_SELECT_BRANCH_OFFICE')
					];
				}

				foreach($response->lista AS $pos => $branchs) {
					$branch = [];

					if($select) {
						$text = ( lang('SETT_BULK_BRANCHOFFICE_VIEW') === 'ADDRESS')? $response->lista[$pos]->direccion_1 : $response->lista[$pos]->nomb_cia;
						$branch['key'] = $response->lista[$pos]->cod;
						$branch['text'] = ucfirst(mb_strtolower($text));
						$branchOffice[] = (object) $branch;
						continue;
					}

					$branch['idFiscal'] = $response->lista[$pos]->rif;
					$branch['name'] = mb_strtoupper($response->lista[$pos]->nomb_cia);
					$branchOffice[] = (object) $branch;
				}
			break;
		}

		if($this->isResponseRc != 0) {
			$this->response->code = 1;
			$branchOffice[] = (object) [
				'key' => '',
				'text' => lang('GEN_TRY_AGAIN')
			];
		}

		$this->response->data->branchOffices = (object) $branchOffice;

		return $this->responseToTheView('callWs_GetBranchOffices');
	}
	/**
	 * @info Método para obtener lista de productos para una empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 12th, 2019
	 */
	public function callWs_GetProducts_Business($dataRequest)
	{
		writeLog('INFO', 'Business Model: getProducts Method Initialized');

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

		$this->dataAccessLog->modulo = 'Negocios';
		$this->dataAccessLog->function = 'Productos';
		$this->dataAccessLog->operation = 'lista de productos';

		$this->dataRequest->idOperation = 'menuEmpresa';
		$this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
		$this->dataRequest->ctipo = isset($dataRequest->type) ? $dataRequest->type : 'A';
		$this->dataRequest->userName = $this->userName;
		$this->dataRequest->idEmpresa = $dataRequest->idFiscal;
		$this->dataRequest->acCodCia = $dataRequest->enterpriseCode;

		if($this->session->has_userdata('thirdEnterprise')) {
			$this->dataRequest->listaCuentasCBP_BDB = $this->session->thirdEnterprise->cbpAccounts;
			$this->dataRequest->listaCuentasICBS_BDB = $this->session->thirdEnterprise->icbsAccounts;
		}

		$response = $this->sendToService('callWs_GetProducts');

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
				$this->response->msg = lang('GEN_WARNING_PRODUCTS_LIST');
			break;
			case -430:
			case -431:
				$this->session->set_flashdata('unauthorized', lang('GEN_SINGLE_SIGNON'));
				redirect(base_url(lang('SETT_LINK_SIGNOUT').lang('SETT_LINK_SIGNOUT_END')), 'Localtion', 302);
				exit;
			break;
			case -432:
			case -433:
				$this->session->set_flashdata('unauthorized', lang('GEN_NO_PERMISSIONS'));
				redirect(base_url(lang('SETT_LINK_SIGNOUT').lang('SETT_LINK_SIGNOUT_END')), 'Localtion', 302);
				exit;
			break;
			case -434:
			case -435:
				$this->session->set_flashdata('unauthorized', lang('BUSINESS_ENTERPRISE_NOT_ASSIGNED'));
				redirect(base_url(lang('SETT_LINK_SIGNOUT').lang('SETT_LINK_SIGNOUT_END')), 'Localtion', 302);
				exit;
			break;
		}

		if($this->response->code != 0 && !$select) {
			$this->response->data->categoriesList = [];
			$this->response->data->brandList = [];
			$this->response->data->productList = [];
		}

		return $this->responseToTheView('callWs_GetProducts');
	}
	/**
	 * @info Método para obtener lista de productos para una empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 12th, 2019
	 */
	public function callWs_GetProductDetail_Business($dataRequest)
	{
		writeLog('INFO', 'Business Model: getProductDetail Method Initialized');

		$this->dataAccessLog->modulo = 'Negocios';
		$this->dataAccessLog->function = 'Producto';
		$this->dataAccessLog->operation = 'Detalle Producto';

		$productName = '';
		$productImg = '';
		$brandName =  '';
		$imgBrand = '';
		$enterpriseInf = $this->session->enterpriseInf;
		$productPrefix = $dataRequest->productPrefix;

		if (!isset($dataRequest->isGet)) {
			$productName = trim(ucwords(mb_strtolower($dataRequest->productName)));
			$productImg = trim($dataRequest->productImg);
			$brandName = trim($dataRequest->productBrand);
			$imgBrand = mb_strtolower($brandName);
		}

		if (isset($dataRequest->goToDetail)) {
			unset($dataRequest->goToDetail, $dataRequest->productPrefix);
			$enterpriseInf = $dataRequest;
			$this->session->unset_userdata('productInf');
			$this->session->set_userdata('enterpriseInf', $dataRequest);
		}

		$this->dataRequest->idOperation = 'menuPorProducto';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoMenuMO';
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

		$response = $this->sendToService('callWs_GetProductDetail');

		$productDetail = [
			'name' => $productName,
			'productImg' => $productImg,
			'brand' => $brandName,
			'imgBrand' => $imgBrand,
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
				writeLog('INFO', '['.$this->userName.'] '.'callWs_GetProductDetail'.' USER_ACCESS LIST: '.json_encode($response->lista));

				$this->response->code = 0;

				if(isset($response->estadistica->producto->idProducto)) {
					if (isset($dataRequest->isGet)) {
						$productName = trim(ucwords(mb_strtolower($response->estadistica->producto->descripcion)));
						$productImgName = normalizeName(mb_strtolower($productName));
						$productImg = lang('IMG_PROGRAM_IMG_DEFAULT');

						if (array_key_exists($productImgName, lang('IMG_PROGRAM_IMAGES'))) {
							$productImg = lang('IMG_PROGRAM_IMAGES')[$productImgName].'.svg';
						}

						$brandName = trim($response->estadistica->producto->marca);
						$imgBrand = url_title(trim(mb_strtolower($brandName)));
					}

					if (trim($response->estadistica->producto->idProducto) == 'G') {
						$productDetail['viewSomeAttr'] = FALSE;
					}

					$productDetail['name'] = $productName;
					$productDetail['productImg'] = $productImg;
					$productDetail['brand'] = $brandName;
					$productInf = new stdClass();
					$productInf->productPrefix = $productPrefix;
					$productInf->productName = $productName;
					$productInf->brand = $brandName;
					$sess = [
						'productInf' => $productInf,
						'user_access' => $response->lista
					];
					$this->session->set_userdata($sess);
				} else {
					$this->response->code = 3;
					$this->response->title = lang('GEN_PRODUCTS_DETAIL_TITLE');
					$this->response->msg = lang('GEN_UNCONFIGURED_PRODUCT');
					$this->response->modalBtn['btn1']['link'] = lang('SETT_LINK_PRODUCTS');
				}

				$productSummary['lots'] = trim($response->estadistica->lote->total);
				$productSummary['toSign'] = trim($response->estadistica->lote->numPorFirmar);
				$productSummary['toAuthorize'] = trim($response->estadistica->lote->numPorAutorizar);


				if(isset($response->estadistica->ordenServicio)) {
					$productSummary['serviceOrders'] = trim($response->estadistica->ordenServicio->Total);
					$productSummary['serviceOrdersCon'] = trim($response->estadistica->ordenServicio->numConciliada);
					$productSummary['serviceOrdersNoCon'] = trim($response->estadistica->ordenServicio->numNoConciliada);
				}

				if(isset($response->estadistica->listadoTarjeta)) {
					$productSummary['totalCards'] = trim($response->estadistica->listadoTarjeta->numeroTarjetas);
					$productSummary['activeCards'] = trim($response->estadistica->listadoTarjeta->numTarjetasActivas);
					$productSummary['inactiveCards'] = trim($response->estadistica->listadoTarjeta->numTarjetasInactivas);
				}

				if(isset($response->estadistica->producto->mesesVencimiento)) {
					$expMaxMonths = trim($response->estadistica->producto->mesesVencimiento);
					$currentDate = date('Y-m');
					$newDate = strtotime('+'.$expMaxMonths.' month' , strtotime($currentDate));
					$expireDate = date('m/Y' , $newDate);
					$productInf->expMaxMonths = $expireDate;
					$productInf->maxCards = trim($response->estadistica->producto->maxTarjetas);
					$this->session->set_userdata('productInf', $productInf);
				}
			break;
			case -38:
				$this->response->code = 3;
				$this->response->msg = lang('BUSINESS_NO_PRODUCT_INFO');
				$this->response->modalBtn['btn1']['link'] = lang('SETT_LINK_PRODUCTS');
			break;
			case -99:
				$this->response->code = 3;
				$this->response->msg = novoLang(lang('GEN_NO_ACCESS'), $this->userName);
				$this->response->modalBtn['btn1']['link'] = lang('SETT_LINK_PRODUCTS');
			break;
		}

		$imgBrand = lang('IMG_BRAND_DEFAULT');
		$tempBrandImg = $productDetail['imgBrand'];

		if(array_key_exists($tempBrandImg, lang('IMG_BRANDS'))) {
			$imgBrand = lang('IMG_BRANDS')[$tempBrandImg];
		}

		$imgBrand = $productDetail['brand'] == 'Visa' ? lang('GEN_DETAIL_BRAND_COLOR') : $imgBrand.= '_card.svg';
		$productDetail['imgBrand'] = $imgBrand;

		$this->response->data->productDetail = (object) $productDetail;
		$this->response->data->productSummary = (object) $productSummary;

		return $this->responseToTheView('callWs_GetProductDetail');
	}
}
