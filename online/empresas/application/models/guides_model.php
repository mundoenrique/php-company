<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Class:  help_model
 * @package models
 * @INFO:   Clase para las operaciones relacionadas al modulo de ayuda
 * @author:
 * Date: 16/05/2018
 * Time: 2:30 pm
 */
class guides_model extends CI_Model
{
    //Método constructor
    public function __construct()
    {
        parent:: __construct();
        //Add languages

        //Add libraries

    }

		public function get_categories()
		{
			//set API params
			$headerAPI = [];
			$body = [
				'distinct' => 'category'
			];
			$urlAPI = 'guides';
			$bodyAPI = json_encode($body);
			$method = 'GET';

			//Helper call
			$responseCeoApi = GetApiContent($urlAPI, $headerAPI, $bodyAPI, $method);
			$resAPI = $responseCeoApi->resAPI;
			$dataResponse = json_decode($resAPI);

			return $dataResponse;
		}

		public function get_guides()
		{
			//set API params
			$headerAPI = [];
			$projection = [
				'title' => 1,
				'category' => 1
			];
			$body = [
				'projection' => $projection
			];
			$urlAPI = 'guides';
			$bodyAPI = json_encode($body);
			$method = 'GET';

			//Helper call
			$responseCeoApi = GetApiContent($urlAPI, $headerAPI, $bodyAPI, $method);
			$resAPI = $responseCeoApi->resAPI;
			$dataResponse = json_decode($resAPI);

			return $dataResponse->items;
		}

		public function get_category_guides($category)
		{
			//set API params
			$headerAPI = [];
			$filter = [
				'category' => $category
			];
			$projection = [
				'title' => 1,
				'category' => 1
			];
			$body = [
				'filter' => $filter,
				'projection' => $projection
			];
			$urlAPI = 'guides';
			$bodyAPI = json_encode($body);
			$method = 'GET';

			//Helper call
			$responseCeoApi = GetApiContent($urlAPI, $headerAPI, $bodyAPI, $method);
			$resAPI = $responseCeoApi->resAPI;
			$dataResponse = json_decode($resAPI);

			return $dataResponse->items;
		}

		public function get_title_info($title_id)
		{
			//set API params
			$headerAPI = [];
			$body = [];
			$urlAPI = 'guides/' . $title_id;
			$bodyAPI = json_encode($body);
			$method = 'GET';

			//Helper call
			$responseCeoApi = GetApiContent($urlAPI, $headerAPI, $bodyAPI, $method);
			$resAPI = $responseCeoApi->resAPI;
			$dataResponse = json_decode($resAPI);

			return $dataResponse->item;
		}
}
