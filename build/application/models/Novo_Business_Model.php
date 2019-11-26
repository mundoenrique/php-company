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
	public function callWs_getProducts_Business($dataRequest = FALSE)
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
								$programImg = url_title(mb_strtolower($value)).'.svg';
								if(!file_exists(assetPath('images/programs/'.$programImg))) {
									$programImg = 'default.svg';
								}
								$products->programImg = $programImg;
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
								$imgBrand = mb_strtolower($value).'_product.svg';
								log_message('INFO', 'BRANDS-------------'.$imgBrand);
								if(!file_exists(assetPath('images/brands/'.$imgBrand))) {
									$imgBrand = 'default.png';
								}
								$products->imgBrand = $imgBrand;
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

		$enterpriseInf = $this->session->getProducts;;

		$this->dataRequest->idOperation = 'menuPorProducto';
		$this->dataRequest->menus = [
			[
				'app' => 'EOL',
				'prod' => $dataRequest->productPrefix,
				'idUsuario' => $this->userName,
				'idEmpresa' => $enterpriseInf->idFiscal,
			]
		];
		$this->dataRequest->estadistica = [
			'producto' => [
				'prefijo' => $dataRequest->productPrefix,
				'rifEmpresa' => $enterpriseInf->idFiscal,
				'acCodCia' => $enterpriseInf->enterpriseCode,
				'acCodGrupo' => $enterpriseInf->enterpriseGroup
			]
		];

		$response = $this->sendToService(lang('GEN_GET_PRODUCTS_DETAIL'));


		$this->response->data->widget = $enterpriseInf;

		$productDetail = [
			'name' => '--',
			'img' => '--',
			'brand' => '--',
			'imgBrand' => '--'
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
				log_message('INFO', 'NOVO Business Model: getProductDetail USER_ACCESS LIST '.json_encode($response->lista));
				$this->session->set_userdata('user_access', $response->lista);
				$menu = json_decode('[{"idPerfil":"GESLOT","modulos":[{"idModulo":"TEBCAR","descripcion":"CARGA DE LOTES","status":"A","funciones":[{"accodfuncion":"TEBCON","acnomfuncion":"CONFIRMACION DE LOTE"},{"accodfuncion":"TEBELC","acnomfuncion":"ELIMINACIÓN DE LOTE POR CONFIRMAR"}],"rc":0},{"idModulo":"TEBAUT","descripcion":"AUTORIZACION DE LOTES","status":"A","funciones":[{"accodfuncion":"TEBAUT","acnomfuncion":"CONSULTA DE MODULO"},{"accodfuncion":"TEBELI","acnomfuncion":"ELIMINACION DE LOTE"}],"rc":0},{"idModulo":"TEBGUR","descripcion":"REPROCESO DE DATOS","status":"A","funciones":[{"accodfuncion":"TEBGUR","acnomfuncion":"CONSULTA DE MODULO"},{"accodfuncion":"MODIFY","acnomfuncion":"ACTUALIZACION DE DATOS"},{"accodfuncion":"REPROC","acnomfuncion":"REPROCESO DE DATOS"},{"accodfuncion":"INCLUI","acnomfuncion":"INCLUSION DE DATOS"}],"rc":0},{"idModulo":"TICARG","descripcion":"SOLICITUD - TARJETAS INNOMINADAS","status":"A","funciones":[{"accodfuncion":"TICREA","acnomfuncion":"GENERACIÓN DE LOTE"},{"accodfuncion":"TIELIM","acnomfuncion":"ELIMINACIÓN DE LOTE"}],"rc":0},{"idModulo":"TIINVN","descripcion":"INVENTARIO - TARJETAS INNOMINADAS","status":"A","funciones":[{"accodfuncion":"TIREPO","acnomfuncion":"REPORTE DE INNOMINADAS"}],"rc":0}]},{"idPerfil":"CONSUL","modulos":[{"idModulo":"TEBORS","descripcion":"ORDEN DE SERVICIO","status":"A","funciones":[{"accodfuncion":"TEBANU","acnomfuncion":"ANULAR ORDEN DE SERVICIO"},{"accodfuncion":"TEBCOS","acnomfuncion":"CONSULTAR ORDEN DE SERVICIO"}],"rc":0},{"idModulo":"TEBPOL","descripcion":"Actualización de datos","status":"A","funciones":[{"accodfuncion":"TEBANU","acnomfuncion":"ANULAR ORDEN DE SERVICIO"},{"accodfuncion":"TEBCOS","acnomfuncion":"CONSULTAR ORDEN DE SERVICIO"}],"rc":0}]},{"idPerfil":"SERVIC","modulos":[{"idModulo":"TRAMAE","descripcion":"TRANSFERENCIA MAESTRA","status":"A","funciones":[{"accodfuncion":"TRAABO","acnomfuncion":"ABONOS A TARJETAS"},{"accodfuncion":"TRACAR","acnomfuncion":"CARGOS A TARJETAS"},{"accodfuncion":"TRASAL","acnomfuncion":"CONSULTA A TARJETAS"}],"rc":0},{"idModulo":"CONVIS","descripcion":"CONTROLES TARJETAS VISA","status":"A","funciones":[{"accodfuncion":"CONVIS","acnomfuncion":"CONSULTA DE CONTROLES"},{"accodfuncion":"CONVIS","acnomfuncion":"CONSULTA DE CONTROLES"},{"accodfuncion":"CONVIS","acnomfuncion":"CONSULTA DE CONTROLES"}],"rc":0},{"idModulo":"PAGPRO","descripcion":"PAGO A PROVEEDORES","status":"A","funciones":[{"accodfuncion":"PAGPRO","acnomfuncion":"PAGO A PROVEEDORES"},{"accodfuncion":"PAGPRO","acnomfuncion":"PAGO A PROVEEDORES"},{"accodfuncion":"PAGPRO","acnomfuncion":"PAGO A PROVEEDORES"}],"rc":0},{"idModulo":"COPELO","descripcion":"CONSULTA TARJETAS - RECEPCION EMPRESA","status":"A","funciones":[{"accodfuncion":"OPCONL","acnomfuncion":"CONSULTA TARJETAS - RECEPCION EMPRESA"}],"rc":0}]},{"idPerfil":"COMBUS","modulos":[{"idModulo":"CMBCON","descripcion":"COMBUSTIBLE - CONDUCTORES","status":"A","funciones":[{"accodfuncion":"CNDELI","acnomfuncion":"ELIMINAR CONDUCTOR"},{"accodfuncion":"CNDINS","acnomfuncion":"ACTUALIZAR CONDUCTOR"},{"accodfuncion":"CNDINS","acnomfuncion":"ACTUALIZAR CONDUCTOR"},{"accodfuncion":"CNDCON","acnomfuncion":"CONSULTAR CONDUCTORES"},{"accodfuncion":"CNDELI","acnomfuncion":"ELIMINAR CONDUCTOR"},{"accodfuncion":"CNDELI","acnomfuncion":"ELIMINAR CONDUCTOR"},{"accodfuncion":"CNDINS","acnomfuncion":"ACTUALIZAR CONDUCTOR"},{"accodfuncion":"CNDINS","acnomfuncion":"ACTUALIZAR CONDUCTOR"},{"accodfuncion":"CNDCON","acnomfuncion":"CONSULTAR CONDUCTORES"},{"accodfuncion":"CNDELI","acnomfuncion":"ELIMINAR CONDUCTOR"},{"accodfuncion":"CNDCON","acnomfuncion":"CONSULTAR CONDUCTORES"},{"accodfuncion":"CNDCON","acnomfuncion":"CONSULTAR CONDUCTORES"}],"rc":0},{"idModulo":"CMBVHI","descripcion":"COMBUSTIBLE - VEHICULOS","status":"A","funciones":[{"accodfuncion":"VHIELI","acnomfuncion":"ELIMINAR VEHICULO"},{"accodfuncion":"VHIELI","acnomfuncion":"ELIMINAR VEHICULO"},{"accodfuncion":"VHIINS","acnomfuncion":"ACTUALIZAR VEHICULO"},{"accodfuncion":"VHIINS","acnomfuncion":"ACTUALIZAR VEHICULO"},{"accodfuncion":"VHICON","acnomfuncion":"CONSULTAR VEHICULO"},{"accodfuncion":"VHICON","acnomfuncion":"CONSULTAR VEHICULO"},{"accodfuncion":"VHIELI","acnomfuncion":"ELIMINAR VEHICULO"},{"accodfuncion":"VHIELI","acnomfuncion":"ELIMINAR VEHICULO"},{"accodfuncion":"VHIINS","acnomfuncion":"ACTUALIZAR VEHICULO"},{"accodfuncion":"VHIINS","acnomfuncion":"ACTUALIZAR VEHICULO"},{"accodfuncion":"VHICON","acnomfuncion":"CONSULTAR VEHICULO"},{"accodfuncion":"VHICON","acnomfuncion":"CONSULTAR VEHICULO"}],"rc":0},{"idModulo":"CMBCTA","descripcion":"COMBUSTIBLE - CUENTAS","status":"A","funciones":[{"accodfuncion":"CTAINS","acnomfuncion":"ACTUALIZAR CUENTAS"},{"accodfuncion":"CTAELI","acnomfuncion":"ELIMINAR CUENTAS"},{"accodfuncion":"CTAELI","acnomfuncion":"ELIMINAR CUENTAS"},{"accodfuncion":"CTACON","acnomfuncion":"CONSULTAR CUENTAS"},{"accodfuncion":"CTACON","acnomfuncion":"CONSULTAR CUENTAS"},{"accodfuncion":"CTAINS","acnomfuncion":"ACTUALIZAR CUENTAS"},{"accodfuncion":"CTAINS","acnomfuncion":"ACTUALIZAR CUENTAS"},{"accodfuncion":"CTAELI","acnomfuncion":"ELIMINAR CUENTAS"},{"accodfuncion":"CTAELI","acnomfuncion":"ELIMINAR CUENTAS"},{"accodfuncion":"CTACON","acnomfuncion":"CONSULTAR CUENTAS"},{"accodfuncion":"CTACON","acnomfuncion":"CONSULTAR CUENTAS"},{"accodfuncion":"CTAINS","acnomfuncion":"ACTUALIZAR CUENTAS"}],"rc":0},{"idModulo":"CMBVJE","descripcion":"COMBUSTIBLE - VIAJES","status":"A","funciones":[{"accodfuncion":"VJECND","acnomfuncion":"ASIGNAR VIAJE A CONDUCTOR"},{"accodfuncion":"VJECND","acnomfuncion":"ASIGNAR VIAJE A CONDUCTOR"},{"accodfuncion":"VJEINS","acnomfuncion":"CREAR VIAJE"},{"accodfuncion":"VJECON","acnomfuncion":"CONSULTAR VIAJES"},{"accodfuncion":"VJECON","acnomfuncion":"CONSULTAR VIAJES"},{"accodfuncion":"VJEELI","acnomfuncion":"ELIMINAR VIAJES"},{"accodfuncion":"VJEELI","acnomfuncion":"ELIMINAR VIAJES"},{"accodfuncion":"VJEINS","acnomfuncion":"CREAR VIAJE"},{"accodfuncion":"VJEINS","acnomfuncion":"CREAR VIAJE"},{"accodfuncion":"VJECND","acnomfuncion":"ASIGNAR VIAJE A CONDUCTOR"},{"accodfuncion":"VJECND","acnomfuncion":"ASIGNAR VIAJE A CONDUCTOR"},{"accodfuncion":"VJECON","acnomfuncion":"CONSULTAR VIAJES"},{"accodfuncion":"VJECON","acnomfuncion":"CONSULTAR VIAJES"},{"accodfuncion":"VJEINS","acnomfuncion":"CREAR VIAJE"},{"accodfuncion":"VJEELI","acnomfuncion":"ELIMINAR VIAJES"},{"accodfuncion":"VJEELI","acnomfuncion":"ELIMINAR VIAJES"}],"rc":0}]},{"idPerfil":"GESREP","modulos":[{"idModulo":"REPEDO","descripcion":"REPORTE DE ESTADOS DE CUENTA","status":"A","funciones":[{"accodfuncion":"REPEDO","acnomfuncion":"REPORTE DE ESTADOS DE CUENTA"}],"rc":0},{"idModulo":"REPSAL","descripcion":"REPORTE SALDOS AL CIERRE","status":"A","funciones":[{"accodfuncion":"REPSAL","acnomfuncion":"REPORTE SALDOS AL CIERRE"}],"rc":0},{"idModulo":"REPUSU","descripcion":"REPORTE DE USUARIOS EMPRESA","status":"A","funciones":[{"accodfuncion":"REPUSU","acnomfuncion":"REPORTE ACTIVIDAD POR USUARIO"}],"rc":0},{"idModulo":"REPPRO","descripcion":"REPORTE RECARGAS POR PRODUCTO","status":"A","funciones":[{"accodfuncion":"REPPRO","acnomfuncion":"REPORTE RECARGAS REALIZADAS"}],"rc":0},{"idModulo":"REPTAR","descripcion":"REPORTE TARJETAS EMITIDAS","status":"A","funciones":[{"accodfuncion":"REPTAR","acnomfuncion":"REPORTE TARJETAS EMITIDAS"}],"rc":0},{"idModulo":"REPLOT","descripcion":"REPORTE DE ESTATUS DE LOTES","status":"A","funciones":[{"accodfuncion":"REPLOT","acnomfuncion":"REPORTE ESTATUS DE LOTES"}],"rc":0},{"idModulo":"REPCAT","descripcion":"REPORTES DE CATEGORIA DE COMER","status":"A","funciones":[{"accodfuncion":"REPCAT","acnomfuncion":"REPORTES GASTOS POR CATEGORIAS"}],"rc":0},{"idModulo":"REPCON","descripcion":"REPORTE CUENTA CONCENTRADORA","status":"A","funciones":[{"accodfuncion":"REPCON","acnomfuncion":"REPORTE CUENTA CONCENTRADORA"},{"accodfuncion":"TEBCOD","acnomfuncion":"GENERAR CONSOLIDADO"}],"rc":0}]}]');
				$this->session->set_userdata('menuArrayPorProducto', serialize($menu));
				$imgBrand = url_title(trim(mb_strtolower($response->estadistica->producto->marca))).'_card.svg';

				if(!file_exists(assetPath('images/brands/'.$imgBrand))) {
					$imgBrand = 'default.png';
				}

				$imgProgram = url_title(trim(mb_strtolower($response->estadistica->producto->nombre))).'.svg';

				if(!file_exists(assetPath('images/programs/'.$imgProgram))) {
					$imgProgram = 'default.svg';
				}

				$productDetail['name'] = ucwords(mb_strtolower($response->estadistica->producto->descripcion));
				$productDetail['img'] = $imgProgram;
				$productDetail['brand'] = trim($response->estadistica->producto->marca);
				$productDetail['imgBrand'] = $imgBrand;
				$productSummary['lots'] = trim($response->estadistica->lote->total);
				$productSummary['toSign'] = trim($response->estadistica->lote->numPorFirmar);
				$productSummary['toAuthorize'] = trim($response->estadistica->lote->numPorAutorizar);
				$productSummary['serviceOrders'] = trim($response->estadistica->ordenServicio->Total);
				$productSummary['serviceOrdersCon'] = trim($response->estadistica->ordenServicio->numConciliada);
				$productSummary['serviceOrdersNoCon'] = trim($response->estadistica->ordenServicio->numNoConciliada);
				$productSummary['totalCards'] = trim($response->estadistica->listadoTarjeta->numeroTarjetas);
				$productSummary['activeCards'] = trim($response->estadistica->listadoTarjeta->numTarjetasActivas);
				$productSummary['inactiveCards'] = trim($response->estadistica->listadoTarjeta->numTarjetasInactivas);

				$this->response->data->productDetail = (object) $productDetail;
				$this->response->data->productSummary = (object) $productSummary;

				$expMax = new stdClass();
				$expMax->expMaxMonths = trim($response->estadistica->producto->mesesVencimiento);
				$expMax->maxCards = trim($response->estadistica->producto->maxTarjetas);

				$this->session->set_userdata('expMax', $expMax);
				break;
		}

		return $this->responseToTheView(lang('GEN_GET_PRODUCTS_DETAIL'));
	}
}
