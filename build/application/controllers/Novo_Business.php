<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a empresas
 * @author J. Enrique Peñaloza Piñero
 * @date October 30th, 2019
*/
class Novo_Business extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		writeLog('INFO', 'Business Controller Class Initialized');
	}
	/**
	 * @info Método para renderizar las empresas asociadas al usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 05th, 2019
	 */
	public function getEnterprises()
	{
		writeLog('INFO', 'Business: getEnterprises Method Initialized');

		$view = 'getEnterprises';
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/isotope.pkgd-3.0.6",
			"third_party/pagination-2.1.4",
			"business/enterprise",
			"option-search"
		);

		$responseList = $this->loadModel();
		$this->responseAttr($responseList, FALSE);
		$this->render->category = "";
		$this->render->lastSession = $this->session->lastSession;
		$this->render->enterprisesTotal = $responseList->data->enterprisesTotal;
		$this->render->enterpriseList = $responseList->data->list;
		$this->render->filters = $responseList->data->filters;
		$this->render->recordsPage = $responseList->data->recordsPage;
		$this->render->msgEnterprise = $responseList->data->text;
		$this->render->disabled = $responseList->code === 0 ?? 'disabled';
		$this->render->titlePage = lang('BUSINESS_ENTERPRISE_TITLE');
		$this->views = ['business/' . $view];
		$this->loadView($view);
	}
	/**
	 * @info Método para renderizarlos productos asociados a la empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 12th, 2019
	 */
	public function getProducts()
	{
		writeLog('INFO', 'Business: getProducts Method Initialized');

		$requestArray = (array)$this->request;

		if(empty($requestArray) && !$this->session->has_userdata('enterpriseInf')) {
			redirect(base_url(lang('SETT_LINK_ENTERPRISES')), 'Location', 302);
			exit;
		}

		$view = 'getProducts';
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/polyfill-6.26.0",
			"third_party/jplist-1.2.0",
			"option-search",
			"business/products"
		);

		if(empty($requestArray)) {
			$request = $this->session->enterpriseInf;
			$this->request->enterpriseCode = $request->enterpriseCode;
			$this->request->enterpriseGroup = $request->enterpriseGroup;
			$this->request->idFiscal = $request->idFiscal;
			$this->request->enterpriseName = $request->enterpriseName;
			$this->request->thirdApp = $request->thirdApp;
			$this->request->fiscalNumber = $request->fiscalNumber;
			$this->request->operatingModel = $request->operatingModel;
		}

		$responseList = $this->loadModel($this->request);
		$this->responseAttr($responseList);
		$this->render->brands = $responseList->data->brandList;
		$this->render->categories = $responseList->data->categoriesList;
		$this->render->productList = $responseList->data->productList;

		if($this->render->widget && count($this->render->enterpriseList) < 2) {
			$this->render->widget = FALSE;
		}

		if($this->render->widget) {
			$this->render->widget->products = FALSE;
			$this->render->widget->widgetBtnTitle = lang('GEN_SELECT_ENTERPRISE');
			$this->render->widget->hasProducts = FALSE;
			$this->render->widget->actionForm = lang('SETT_LINK_PRODUCTS');
		}

		$this->render->titlePage = lang('GEN_PRODUCTS');
		$this->views = ['business/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para renderizar la vista consolidada del producto
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 22th, 2019
	 */
	public function getProductDetail()
	{
		writeLog('INFO', 'Business: getProductDetail Method Initialized');

		$requestArray = (array) $this->request;

		if(empty($requestArray) && !$this->session->has_userdata('productInf')) {
			redirect(base_url(lang('SETT_LINK_ENTERPRISES')), 'Location', 302);
			exit;
		}

		$view = 'getProductDetail';
		array_push(
			$this->includeAssets->jsFiles,
			"business/getProductDetail"
		);

		if(empty($requestArray)) {
			$request = $this->session->productInf;
			$this->request->isGet = TRUE;
			$this->request->productPrefix = $request->productPrefix;
		}

		$detailList = $this->loadModel($this->request);
		$this->responseAttr($detailList);
		$this->render->detailProductName = $detailList->data->productDetail->name;
		$this->render->productImg = $detailList->data->productDetail->productImg;
		$this->render->productImgBrand = $detailList->data->productDetail->imgBrand;
		$this->render->productBrand = $detailList->data->productDetail->brand;
		$this->render->viewSomeAttr = $detailList->data->productDetail->viewSomeAttr;
		$this->render->loadBulkLink = $this->verify_access->verifyAuthorization('TEBCAR') ? lang('SETT_LINK_BULK_LOAD') : lang('SETT_NO_LINK');
		$this->render->loadDisabled = $this->render->loadBulkLink == lang('SETT_NO_LINK') ? 'is-disabled' : '';
		$this->render->bulkAuthLink = $this->verify_access->verifyAuthorization('TEBAUT') ? lang('SETT_LINK_BULK_AUTH') : lang('SETT_NO_LINK');
		$this->render->authDisabled = $this->render->bulkAuthLink == lang('SETT_NO_LINK') ? 'is-disabled' : '';
		$this->render->lotsTotal = $detailList->data->productSummary->lots;
		$this->render->toSign = $detailList->data->productSummary->toSign;
		$this->render->toAuthorize = $detailList->data->productSummary->toAuthorize;
		$this->render->OrderServLink = $this->verify_access->verifyAuthorization('TEBORS') ? lang('SETT_LINK_SERVICE_ORDERS') : lang('SETT_NO_LINK');
		$this->render->orderDisabled = $this->render->OrderServLink == lang('SETT_NO_LINK') ? 'is-disabled' : '';
		$this->render->serviceOrders = $detailList->data->productSummary->serviceOrders;
		$this->render->serviceOrdersNoCon = $detailList->data->productSummary->serviceOrdersNoCon;
		$this->render->serviceOrdersCon = $detailList->data->productSummary->serviceOrdersCon;
		$this->render->masterTransLink = $this->verify_access->verifyAuthorization('TRAMAE') ? lang('SETT_LINK_TRANSF_MASTER_ACCOUNT') : lang('SETT_NO_LINK');
		$this->render->masterTransDisabled = $this->render->masterTransLink == lang('SETT_NO_LINK') ? 'is-disabled' : '';
		$this->render->totalCards = $detailList->data->productSummary->totalCards;
		$this->render->activeCards = $detailList->data->productSummary->activeCards;
		$this->render->inactiveCards = $detailList->data->productSummary->inactiveCards;
		$this->render->titlePage = lang('GEN_PRODUCTS_DETAIL_TITLE');
		$this->views = ['business/'.$view];
		$this->loadView($view);
	}
}
