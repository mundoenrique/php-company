<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Libreria peticiones get de la aplicación
 * @author J. Enrique Peñaloza Piñero
 * @date November 21st, 2019
 */
class Request_Data {
	private $CI;
	private $NOVO_Model;

	public function __construct()
	{
		writeLog('INFO', 'Request_Data Library Class Initialized');

		$this->CI = &get_instance();
	}
	/**
	 * @info Método para obtener el tamaño del ancho de la pagina
	 * @author J. Enrique Peñaloza Piñero.
	 * @date November 21st, 2019
	 */
	public function setPageSize($screenSize)
	{
		writeLog('INFO', 'Request_Data: setPageSize Method Initialized');

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

		return $sizePage;
	}
	/**
	 * @info Método para ordenar lista de empresas para vista consolidada
	 * @author J. Enrique Peñaloza Piñero.
	 * @date November 21st, 2019
	 */
	public function OrderEnterpriseList($enterpriseArgs, $filters, $dataRequest)
	{
		writeLog('INFO', 'Request_Data: OrderEnterpriseList Method Initialized');

		$responseList = new stdClass();
		$enterpriseSelect = new stdClass();
		$enterpriseListTemp = $enterpriseArgs->lista;
		$item = 1; $page = 1; $cat = FALSE;
		$itemAlphaBeFi = 1; $itemAlphaBeSec = 1; $itemAlphaBeTh = 1;  $itemAlphaBeFo = 1;
		$itemAlphaBeFif = 1; $itemAlphaBeSi = 1; $itemAlphaBeSev = 1;
		$pageAlphaBeFi = 1; $pageAlphaBeSec = 1; $pageAlphaBeTh = 1;  $pageAlphaBeFo = 1;
		$pageAlphaBeFif = 1; $pageAlphaBeSi = 1; $pageAlphaBeSev = 1;

		foreach($enterpriseArgs->lista AS $pos => $enterprises) {
			if($enterprises->resumenProductos == 0) {
				unset($enterpriseListTemp[$pos]);
			}

			$string = (mb_strtoupper(trim($enterprises->acnomcia)));
			$string = strlen($string) > 30 ? substr($string, 0, 30).'...' : $string;
			$enterprises->enterpriseName = $string;

			foreach($enterprises AS $key => $value) {
				$enterpriseArgs->lista[$pos]->$key = trim($value);

				if($item > $enterpriseArgs->sizePage) {
					$item = 1;
					$page++;
				}

				$enterpriseArgs->lista[$pos]->page = 'page_'.$page;

				if($key === 'resumenProductos') {
					$enterpriseArgs->lista[$pos]->resumenProductos = $value == 1 ?
					$value.' '.lang('GEN_PRODUCT') :
					$value.' '.lang('GEN_PRODUCTS');
				}

				if($key === 'acpercontac') {
					$enterpriseArgs->lista[$pos]->acpercontac = ucwords(mb_strtolower($value));
				}

				if($key === 'acnomcia') {
					preg_match('/([^\W])/', mb_strtoupper($value), $matches);
					$cat = substr(reset($matches), 0, 1);
					$enterpriseArgs->lista[$pos]->category = $cat;

					switch ($cat) {
						case strpos('ABC', $cat) !== FALSE:
							if($itemAlphaBeFi > $enterpriseArgs->sizePage) {
								$itemAlphaBeFi = 1; 	$pageAlphaBeFi++;
							}

							$enterpriseArgs->lista[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_1').'_'.$pageAlphaBeFi;
							$itemAlphaBeFi++;

							if(!$filters->FIRST['active']) {
								$filters->FIRST['active'] = TRUE;
							}
						break;
						case strpos('DEFG', $cat) !== FALSE:
							if($itemAlphaBeSec > $enterpriseArgs->sizePage) {
								$itemAlphaBeSec = 1; 	$pageAlphaBeSec++;
							}

							$enterpriseArgs->lista[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_2').'_'.$pageAlphaBeSec;
							$itemAlphaBeSec++;

							if(!$filters->SECOND['active']) {
								$filters->SECOND['active'] = TRUE;
							}
						break;
						case strpos('HIJK', $cat) !== FALSE:
							if($itemAlphaBeTh > $enterpriseArgs->sizePage) {
								$itemAlphaBeTh = 1; 	$pageAlphaBeTh++;
							}

							$enterpriseArgs->lista[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_3').'_'.$pageAlphaBeTh;
							$itemAlphaBeTh++;

							if(!$filters->THIRD['active']) {
								$filters->THIRD['active'] = TRUE;
							}
						break;
						case strpos('LMNO', $cat) !== FALSE:
							if($itemAlphaBeFo > $enterpriseArgs->sizePage) {
								$itemAlphaBeFo = 1; 	$pageAlphaBeFo++;
							}

							$enterpriseArgs->lista[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_4').'_'.$pageAlphaBeFo;
							$itemAlphaBeFo++;

							if(!$filters->FOURTH['active']) {
								$filters->FOURTH['active'] = TRUE;
							}
						break;
						case strpos('PQRS', $cat) !== FALSE:
							if($itemAlphaBeFi > $enterpriseArgs->sizePage) {
								$itemAlphaBeFi = 1; 	$pageAlphaBeFif++;
							}

							$enterpriseArgs->lista[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_5').'_'.$pageAlphaBeFif;
							$itemAlphaBeFif++;

							if(!$filters->FIFTH['active']) {
								$filters->FIFTH['active'] = TRUE;
							}
						break;
						case strpos('TUVW', $cat) !== FALSE:
							if($itemAlphaBeSi > $enterpriseArgs->sizePage) {
								$itemAlphaBeSi = 1; 	$pageAlphaBeSi++;
							}

							$enterpriseArgs->lista[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_6').'_'.$pageAlphaBeSi;
							$itemAlphaBeSi++;

							if(!$filters->SIXTH['active']) {
								$filters->SIXTH['active'] = TRUE;
							}
						break;
						case strpos('XYZ', $cat) !== FALSE:
							if($itemAlphaBeSev > $enterpriseArgs->sizePage) {
								$itemAlphaBeSev = 1; 	$pageAlphaBeSev++;
							}

							$enterpriseArgs->lista[$pos]->albeticalPage = lang('ENTERPRISE_FILTER_7').'_'.$pageAlphaBeSev;
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

		$enterpriseSelect->list = array_values($enterpriseListTemp);
		$this->CI->session->set_userdata('enterpriseSelect', $enterpriseSelect);
		$responseList->list = $enterpriseArgs->lista;
		$responseList->filters = $filters;

		return $responseList;
	}
	/**
	 * @info Método para obtener los filtros por pagina del listado de empresas
	 * @author J. Enrique Peñaloza Piñero.
	 * @date November 21st, 2019
	 */
	public function setFilters()
	{
		writeLog('INFO', 'Request_Data: setFilters Method Initialized');

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

		return $filters;
	}
	/**
	 * @info Método para ordenar los productos asociados a una empresa
	 * @author J. Enrique Peñaloza Piñero.
	 * @date December 5th, 2019
	 */
	public function getProductsOrder($responseList, $select)
	{
		writeLog('INFO', 'Request_Data: getProductsOrder Method Initialized');

		if($select) {
			return $this->orderToSelectList($responseList);
		} else {
			return $this->orderToProductList($responseList);
		}

	}
	/**
	 * @info Método para ordenar los productos para la lista de productos
	 * @author J. Enrique Peñaloza Piñero.
	 * @date December 5th, 2019
	 */
	public function orderToProductList($response)
	{
		writeLog('INFO', 'Request_Data: orderToProductList Method Initialized');

		$noDeleteCat = [];
		$noDeleteBrand = [];

		foreach($response->productos AS $pos => $products) {
			foreach($products AS $key => $value) {
				switch ($key) {
					case 'descripcion':
						$productImgName = normalizeName(mb_strtolower($value));
						$productImg = lang('IMG_PROGRAM_IMG_DEFAULT');

						if (array_key_exists($productImgName, lang('IMG_PROGRAM_IMAGES'))) {
							$productImg = lang('IMG_PROGRAM_IMAGES')[$productImgName].'.svg';
						}

						$products->productImg = $productImg;
						$products->$key = trim(mb_strtoupper($value));
					break;
					case 'categoria':
						$products->$key = trim(ucwords(mb_strtolower($value)));
					break;
					case 'idCategoria':
						$noDeleteCat[] =  $value;
					break;
					case 'filial':
						$products->$key = trim(mb_strtoupper($value));
					break;
					case 'marca':
						$imgBrand = url_title(trim(mb_strtolower($value))).'_product.svg';

						if(!file_exists(assetPath('images/brands/'.$imgBrand))) {
							$imgBrand = 'default.svg';
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

		$productList = new stdClass();
		$productList->categorieList = $categorieList;
		$productList->brandList = $brandList;
		$productList->productList = $response->productos;

		return $productList;
	}
	/**
	 * @info Método para ordenar los productos para la lista de productos
	 * @author J. Enrique Peñaloza Piñero.
	 * @date December 5th, 2019
	 */
	public function orderToSelectList($response)
	{
		writeLog('INFO', 'Request_Data: orderToSelectList Method Initialized');

		$productListSelect = [];

		foreach($response->productos AS $pos => $products) {
			foreach($products AS $key => $value) {
				switch ($key) {
					case 'descripcion':
						$string = (mb_strtoupper(trim($value)));
						$string = strlen($string) > 30 ? substr($string, 0, 30).'...' : $string;
						$productList['desc'] = $string;
						break;
					case 'idProducto':
						$productList['id'] = trim($value);
						break;
					case 'marca':
						$productList['brand'] = trim($value);
						break;
				}
			}

			$productListSelect[] = $productList;
		}

		return $productListSelect;
	}
}
