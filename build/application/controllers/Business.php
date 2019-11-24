<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a empresas
 * @author J. Enrique Peñaloza Piñero
 * @date October 30th, 2019
*/
class Business extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Business Controller class Initialized');
	}
	/**
	 * @info Método para renderizar las empresas asociadas al usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 05th, 2019
	 */
	public function getEnterprises()
	{
		log_message('INFO', 'NOVO Business: getEnterprises Method Initialized');

		$view = lang('GEN_GET_ENTERPRISES');

		array_push(
			$this->includeAssets->jsFiles,
			"third_party/isotope.pkgd-3.0.6",
			"third_party/pagination-2.1.4",
			"business/enterprise",
			"option-search"
		);

		$this->views = ['business/'.$view];
		$responseList = $this->loadModel();
		$this->responseAttr($responseList);
		$this->render->titlePage = lang('ENTERPRISE_TITLE');
		$this->render->category = "";
		$this->render->lastSession = $this->session->userdata('lastSession');
		$this->render->enterprisesTotal = $responseList->data->enterprisesTotal;
		$this->render->enterpriseList = $responseList->data->list;
		$this->render->filters = $responseList->data->filters;
		$this->render->recordsPage = $responseList->data->recordsPage;
		$this->render->msgEnterprise = $responseList->data->text;
		$this->render->disabled = $responseList->code == 0 ?: 'disabled';
		$this->loadView($view);
	}
	/**
	 * @info Método para renderizarlos productos asociados a la empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 12th, 2019
	 */
	public function getProducts()
	{
		log_message('INFO', 'NOVO Business: getProducts Method Initialized');

		$requestArray = (array)$this->request;

		if(empty($requestArray) && !$this->session->has_userdata('getProducts')) {
			redirect(base_url('inicio'), 'location');
		}

		$view = lang('GEN_GET_PRODUCTS');

		array_push(
			$this->includeAssets->jsFiles,
			"option-search",
			"business/widget-enterprise",
			"business/products"
		);

		if(empty($requestArray)) {
			$request = $this->session->getProducts;
			$this->request->enterpriseCode = $request->enterpriseCode;
			$this->request->enterpriseGroup = $request->enterpriseGroup;
			$this->request->idFiscal = $request->idFiscal;
			$this->request->enterpriseName = $request->enterpriseName;
		}

		$responseList = $this->loadModel($this->request);
		$this->responseAttr($responseList);

		$this->render->widget =  new stdClass();
		$this->render->widget->enterpriseList = [];
		if($responseList->code === 0) {
			$this->load->model('Novo_Business_Model', 'Business');
			$enterpriseList = $this->Business->callWs_getEnterprises_Business(TRUE);
			$this->render->widget->enterpriseList =  $enterpriseList->data->list;
		}

		$this->render->titlePage = "Productos";
		$this->render->brands = $responseList->data->brandList;
		$this->render->categories = $responseList->data->categoriesList;
		$this->render->productList = $responseList->data->productList;
		$this->render->widget->products = FALSE;
		$this->render->widget->widgetBtnTitle = lang('PRODUCTS_WIDGET_BTN');
		$this->render->widget->enterpriseData =  $responseList->data->widget;
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
		log_message('INFO', 'NOVO Business: getProductDetail Method Initialized');

		$view = 'product-detail';
		$this->render->titlePage = "Detalle del producto";
		$this->views = ['business/'.$view];
		$this->render->widget =  new stdClass();
		$this->render->widget->widgeBtnTitle = 'Debes seleccionar empresa y producto';
		$this->loadView($view);
	}

}
