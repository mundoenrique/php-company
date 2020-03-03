<?php
defined('BASEPATH') OR  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a consultas de ordenes de servicio
 * @author J. Enrique Peñaloza Piñero
 * @date January 09th, 2019
*/
class DownloadFiles extends NOVO_Controller {

	public function __construct()
	{
		parent :: __construct();
		log_message('INFO', 'NOVO DownloadFiles Controller Class Initialized');
	}

	/**
	 * @info Método para exportar el detalle del lote a xls
	 ** @author Luis Molina
	 * @date marzo 02Mon, 2020
	 */
	public function exportDetailServiceOrders()
	{
		log_message('INFO', 'NOVO Inquiries: exportDetailServiceOrders Method Initialized');

		$this->loadModel($this->request);

	}

	/**
	 * @info Método para exportar la orden de servicio a pdf
	 ** @author Luis Molina
	 * @date marzo 02Mon, 2020
	 */
	public function exportServiceOrders()
	{
		log_message('INFO', 'NOVO Inquiries: exportServiceOrders Method Initialized');

		$this->loadModel($this->request);

	}

}
