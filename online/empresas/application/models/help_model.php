<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Class:  help_model
 * @package models
 * @INFO:   Clase para las operaciones relacionadas al modulo de ayuda
 * @author:
 * Date: 16/05/2018
 * Time: 2:30 pm
 */
class help_model extends CI_Model
{
    //Atributos de la clase
    public $category;
    public $title;
    public $sub_title;
    public $href;
    public $description;
    public $country;
    public $language;
    public $items = [];

    //Método constructor
    public function __construct()
    {
        parent:: __construct();
        //Add languages

        //Add libraries

    }

    //Otros metodos
    public function get_functionalities($message)
		{
			//set API params
			$headerAPI = [];
			$body = [];
			$urlAPI = 'guides';
			$bodyAPI = json_encode($body);
			$method = 'GET';

			//Helper call
			$message1 = $message . ' 2.- Model';
			$message2 = GetApiContent($urlAPI, $headerAPI, $bodyAPI, $method, $message1);

			//response prep


			return $message2;
		}

    public function post_create()
		{
				//call helper for api service
  	}

    public function put_update()
		{
        //call helper for api service
  	}

    public function delete()
		{
        //call helper for api service
  	}
}
