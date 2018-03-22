<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Class:  Reporte_trayectos_Model
 * @package models
 * @INFO:   Clase para la obtención de los reportes de trayectos
 * @author: J Enrique Peñaloza P
 * Date: 22/03/2017
 * Time: 12:50 pm
 */
class Reportes_trayectos extends CI_Model
{
	//Atributos de clase
	protected $pais;
	protected $token;
	protected $company;
	protected $idProducto;

	//Método constructor
	public function __construct()
	{
			parent::__construct();
			//Inicializa atributos de clase
			$this->pais = $this->session->userdata('pais');
			$this->token = $this->session->userdata('token');
			$this->company = $this->session->userdata('acrifS');
			$this->idProducto = $this->session->userdata('idProductoS');
			//Agrega lenguajes
			$this->lang->load('dashboard');
			$this->lang->load('combustible');
			$this->lang->load('users');
			$this->lang->load('erroreseol');
	}
	/*---Fin método constructor---------------------------------------------------------------------*/
	/**
	 * @Method: callAPIDriversReport
	 * @access public
	 * @params:
	 * @params:
	 * @info:
	 * @autor:
	 * @date:
	 */
	public function callAPIDriversReport($dataRquest)
	{
	}

	public function callAPIVehiculosReport($dataRquest)
	{
	}

	public function callAPICuentasReport($dataRquest)
	{
	}

	public function callAPIViajesReport($dataRquest)
	{
	}
}
