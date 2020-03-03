<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los lotes
 * @author
 *
 */
class Novo_DownloadFiles_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO DownloadFiles Model Class Initialized');
	}

	/**
	 * @info Elimina un lote
	 * @author Luis Molina
	 * @date febrero 27 th, 2020
	 */
	public function callWs_exportDetailServiceOrders_downloadFiles($dataRequest)
	{

		log_message('INFO', 'NOVO Inquiries Model: exportDetailServiceOrders Method Initialized');

		$operation='';

		if($dataRequest->file_type=='xls'){
			$operation='detalleLoteExcel';
		}else if ($dataRequest->file_type=='pdf'){
			$operation='detalleLotePDF';
		}

		$this->dataAccessLog->modulo = 'lotes';
		$this->dataAccessLog->function = 'verdetallelote';
		$this->dataAccessLog->operation = 'Ver detalle Lote';
		$this->className = 'com.novo.objects.TOs.LoteTO';
		$this->dataRequest->idOperation = $operation;
		$this->dataRequest->acidlote =$dataRequest->data_lote;

		$response = $this->sendToService('exportDetailServiceOrders');

		if($response->rc==0){
			exportFile($response->archivo,$dataRequest->file_type,$response->nombre);
		}
	}

		/**
	 * @info Elimina un lote
	 * @author Luis Molina
	 * @date febrero 27 th, 2020
	 */
	public function callWs_exportServiceOrders_downloadFiles($dataRequest)
	{

		log_message('INFO', 'NOVO DownloadFiles Model: exportServiceOrders Method Initialized');

		$rifEmpresa = $this->session->userdata('enterpriseInf')->idFiscal;
		$accodciaS = $this->session->userdata('enterpriseInf')->enterpriseCode;
		$acprefix = $this->session->userdata('productInf')->productPrefix;

		$this->dataAccessLog->modulo = 'descargarPDFOS';
		$this->dataAccessLog->function = 'descargarPDFOS';
		$this->dataAccessLog->operation = 'visualizarOS';

		$this->className = 'com.novo.objects.TOs.OrdenServicioTO';
		$this->dataRequest->idOperation = 'visualizarOS';
		$this->dataRequest->rifEmpresa = $rifEmpresa;
		$this->dataRequest->acCodCia = $accodciaS;
		$this->dataRequest->acprefix = $acprefix;
		$this->dataRequest->idOrden =$dataRequest->idOS;

		$response = $this->sendToService('exportDetailServiceOrders');

		if($response->rc==0){
			exportFile($response->archivo,'pdf',str_replace(' ', '_', 'OrdenServicio'.date("d/m/Y H:i")));
		}
	}

}
