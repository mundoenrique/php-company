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
		$item = 1; $page = 1; $TemCategory = NULL;
		$itemAlphabetic1 = 1; $itemAlphabetic2 = 1; $itemAlphabetic3 = 1;  $itemAlphabetic4 = 1;
		$itemAlphabetic5 = 1; $itemAlphabetic6 = 1; $itemAlphabetic7 = 1;
		$pageAlphabetic1 = 1; $pageAlphabetic2 = 1; $pageAlphabetic3 = 1;  $pageAlphabetic4 = 1;
		$pageAlphabetic5 = 1; $pageAlphabetic6 = 1; $pageAlphabetic7 = 1;

		foreach ($enterpriseArgs->lista as $pos => $enterprise) {
			foreach ($enterprise as $key => $value) {
				$enterpriseArgs->lista[$pos]->$key = gettype($value) === 'string' ? trim($value) : $value;
			}

			$enterpriseName = (mb_strtoupper($enterprise->acnomcia));
			$enterpriseName = strlen($enterpriseName) > 30 ? substr($enterpriseName, 0, 29) . '...' : $enterpriseName;
			$enterprise->enterpriseName = $enterpriseName;
			$enterprise->resumenProductos = (int) $enterprise->resumenProductos;
			$enterprise->acdesc = mb_strtoupper($enterprise->acdesc);
			$enterprise->acpercontac = ucwords(mb_strtolower($enterprise->acpercontac));

			if($enterprise->resumenProductos === 0) {
				unset($enterpriseListTemp[$pos]);
			}

			if($item > $enterpriseArgs->sizePage) {
				$item = 1;
				$page++;
			}

			$enterprise->page = 'page_' . $page;
			preg_match('/([^\W])/', $enterpriseName, $matches);
			$TemCategory = substr(reset($matches), 0, 1);
			$enterprise->category = $TemCategory;

			if(strpos('ABC012', $TemCategory) !== FALSE) {
				if($itemAlphabetic1 > $enterpriseArgs->sizePage) {
					$itemAlphabetic1 = 1; $pageAlphabetic1++;
				}

				$enterprise->albeticalPage = lang('ENTERPRISE_FILTER_1') . '_' . $pageAlphabetic1;
				$itemAlphabetic1++;

				if(!$filters->FIRST['active']) {
					$filters->FIRST['active'] = TRUE;
				}
			}

			if(strpos('DEFG3456', $TemCategory) !== FALSE) {
				if($itemAlphabetic2 > $enterpriseArgs->sizePage) {
					$itemAlphabetic2 = 1; $pageAlphabetic2++;
				}

				$enterprise->albeticalPage = lang('ENTERPRISE_FILTER_2') . '_' . $pageAlphabetic2;
				$itemAlphabetic2++;

				if(!$filters->SECOND['active']) {
					$filters->SECOND['active'] = TRUE;
				}
			}

			if(strpos('HIJK789', $TemCategory) !== FALSE) {
				if($itemAlphabetic3 > $enterpriseArgs->sizePage) {
					$itemAlphabetic3 = 1; $pageAlphabetic3++;
				}

				$enterprise->albeticalPage = lang('ENTERPRISE_FILTER_3') . '_' . $pageAlphabetic2;
				$itemAlphabetic3++;

				if(!$filters->THIRD['active']) {
					$filters->THIRD['active'] = TRUE;
				}
			}

			if(strpos('LMNO', $TemCategory) !== FALSE) {
				if($itemAlphabetic4 > $enterpriseArgs->sizePage) {
					$itemAlphabetic4 = 1; $pageAlphabetic4++;
				}

				$enterprise->albeticalPage = lang('ENTERPRISE_FILTER_4') . '_' . $pageAlphabetic4;
				$itemAlphabetic4++;

				if(!$filters->FOURTH['active']) {
					$filters->FOURTH['active'] = TRUE;
				}
			}

			if(strpos('PQRS', $TemCategory) !== FALSE) {
				if($itemAlphabetic5 > $enterpriseArgs->sizePage) {
					$itemAlphabetic5 = 1; $pageAlphabetic5++;
				}

				$enterprise->albeticalPage = lang('ENTERPRISE_FILTER_5') . '_' . $pageAlphabetic5;
				$itemAlphabetic5++;

				if(!$filters->FIFTH['active']) {
					$filters->FIFTH['active'] = TRUE;
				}
			}

			if(strpos('TUVW', $TemCategory) !== FALSE) {
				if($itemAlphabetic6 > $enterpriseArgs->sizePage) {
					$itemAlphabetic6 = 1; $pageAlphabetic6++;
				}

				$enterprise->albeticalPage = lang('ENTERPRISE_FILTER_6') . '_' . $pageAlphabetic6;
				$itemAlphabetic6++;

				if(!$filters->SIXTH['active']) {
					$filters->SIXTH['active'] = TRUE;
				}
			}

			if(strpos('XYZ', $TemCategory) !== FALSE) {
				if($itemAlphabetic7 > $enterpriseArgs->sizePage) {
					$itemAlphabetic7 = 1; $pageAlphabetic7++;
				}

				$enterprise->albeticalPage = lang('ENTERPRISE_FILTER_7') . '_' . $pageAlphabetic7;
				$itemAlphabetic7++;

				if(!$filters->SEVENTH['active']) {
					$filters->SEVENTH['active'] = TRUE;
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
		$programsList = new stdClass();

		foreach($response->productos as $products) {
			$productImg = lang('IMG_PROGRAM_IMG_DEFAULT');
			$brandImg = lang('IMG_BRAND_DEFAULT');
			$productImgName = normalizeName(mb_strtolower($products->descripcion));
			$brand = url_title(trim(mb_strtolower($products->marca)));

			if (array_key_exists($productImgName, lang('IMG_PROGRAM_IMAGES'))) {
				$productImg = lang('IMG_PROGRAM_IMAGES')[$productImgName] . '.svg';
			}

			if(array_key_exists($brand, lang('IMG_BRANDS'))) {
				$brandImg = lang('IMG_BRANDS')[$brand] . '_product.svg';
			}

			$products->productImg = $productImg;
			$products->imgBrand = $brandImg;
			$products->descripcion = trim(mb_strtoupper($products->descripcion));
			$products->categoria = trim(ucwords(mb_strtolower($products->categoria)));
			$noDeleteCat[] = $products->idCategoria;
			$noDeleteBrand[] =  trim($products->marca);
			$products->filial = trim(mb_strtoupper($products->filial));
		}

		$noDeleteCat = array_unique($noDeleteCat);
		sort($noDeleteCat);
		$categorieList = [];

		foreach($response->listaCategorias AS $pos => $categorie) {
			foreach($noDeleteCat AS $item) {
				if($categorie->idCategoria === $item) {
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

		foreach($response->productos as $products) {
			$productList = [];
			$string = $products->descripcion;
			$string = strlen($string) > 30 ? substr($string, 0, 29) .'...' : $string;
			$productList['desc'] = $string;
			$productList['id'] = trim($products->idProducto);
			$productList['brand'] = trim($products->marca);

			$productListSelect[] = $productList;
		}

		return $productListSelect;
	}
}
