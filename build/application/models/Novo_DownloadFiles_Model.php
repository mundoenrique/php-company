<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los lotes
 * @author J. Enrique Peñaloza Piñero
 * @date March 06th, 2020
 */
class Novo_DownloadFiles_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		writeLog('INFO', 'DownloadFiles Model Class Initialized');
	}
	/**
	 * @info Elimina archivos descargados
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 06th, 2020
	 */
	public function callWs_DeleteFile_DownloadFiles($dataRequest)
	{
		writeLog('INFO', 'DownloadFiles Model: DeleteFile Method Initialized');

		unlink(assetPath('downloads/'.$dataRequest->fileName));
		$this->response->code = 0;
		$this->response->data = '';
		$this->response->modalBtn['btn1']['action'] = 'none';
		return $this->responseToTheView('callWs_DeleteFile');
	}
	/**
	 * @info Elimina archivos descargados
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 06th, 2020
	 */
	public function callWs_UnnmamedAffiliate_DownloadFiles($dataRequest)
	{
		writeLog('INFO', 'DownloadFiles Model: UnnmamedAffiliate Method Initialized');

		$this->dataAccessLog->modulo = 'lotes';
		$this->dataAccessLog->function = 'Innominadas';
		$this->dataAccessLog->operation = 'Detalle de lote';

		$this->dataRequest->idOperation = 'generarReporteTarjetasInnominadas';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoLotesMO';
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;
		$this->dataRequest->tarjetasInnominadas = [
			[
				'numLote' => $dataRequest->bulkNumber,
				'idExtEmp' => $this->session->enterpriseInf->idFiscal,
				'estatus' => '0',
				'enmascarar' => TRUE
			]
		];

		$response = $this->sendToService('callWs_UnnmamedDetail');

		switch ($this->isResponseRc) {
			case 0:
				$fileInfo = $response->bean;
				exportFile($fileInfo->archivo, 'xls', 'Afiliacion_innominadas');
			break;
			default:
				$this->load->model('Novo_Bulk_Model', 'DetailUnnamed');
				unset($dataRequest->who, $dataRequest->where);
				$response = $this->DetailUnnamed->callWs_UnnmamedDetail_Bulk($dataRequest);
				$this->response->data->bulkInfo = $response->data->bulkInfo;
				$this->response->data->request = $dataRequest;
				$this->responseFail_DownloadFiles($response);
				$this->session->set_flashdata('download', $this->response);
				redirect(base_url(lang('CONF_LINK_BULK_UNNAMED_DETAIL')), 'Location', 302);
				exit;
		}


		return $this->responseToTheView('callWs_UnnmamedAffiliate');
	}
	/**
	 * @info descarga archivos xls y PDF de reporte estado de lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 16th, 2020
	 */
	public function callWs_BulkDetailExport_DownloadFiles($dataRequest)
	{
		writeLog('INFO', 'DownloadFiles Model: StatusBulkReport Method Initialized');

		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Detalle de lote';
		$this->dataAccessLog->operation = 'Descarga de archivos';

		switch ($dataRequest->type) {
			case lang('GEN_BTN_DOWN_XLS'):
				$this->dataRequest->idOperation = 'detalleLoteExcel';
				$FileType = 'xls';
			break;
			case lang('GEN_BTN_DOWN_PDF'):
				$this->dataRequest->idOperation = 'detalleLotePDF';
				$FileType = 'pdf';
			break;
		}

		$this->dataRequest->className = 'com.novo.objects.TOs.LoteTO';
		$this->dataRequest->acidlote = $dataRequest->bulkId;

		$response = $this->sendToService('callWs_StatusBulkReport');

		switch ($this->isResponseRc) {
			case 0:
				exportFile($response->archivo, $FileType, $response->nombre);
			break;
			default:
				$dataRequest->code = 0;
				$request = new stdClass();
				$request->bulkId = $dataRequest->bulkId;
				$request->bulkfunction = $dataRequest->bulkfunction;
				$this->response->data->request = $request;
				$this->responseFail_DownloadFiles($dataRequest);
				$this->session->set_flashdata('download', $this->response);
				redirect(base_url(lang('CONF_LINK_INQUIRY_BULK_DETAIL')), 'Location', 302);
				exit;
		}


		return $this->responseToTheView('callWs_UnnmamedAffiliate');
	}
	/**
	 * @info descarga archivos xls y PDF de reporte estado de lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 16th, 2020
	 */
	public function callWs_StatusBulkReport_DownloadFiles($dataRequest)
	{
		writeLog('INFO', 'DownloadFiles Model: StatusBulkReport Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Estado de Lote';
		$this->dataAccessLog->operation = 'Descarga de archivos';

		switch ($dataRequest->type) {
			case lang('GEN_BTN_DOWN_XLS'):
				$this->dataRequest->idOperation = 'generarArchivoXlsEstatusLotes';
				$FileType = 'xls';
			break;
			case lang('GEN_BTN_DOWN_PDF'):
				$this->dataRequest->idOperation = 'generarPdfEstatusLotes';
				$FileType = 'pdf';
			break;
		}

		$this->dataRequest->className = 'com.novo.objects.MO.ListadoLotesMO';
		$this->dataRequest->acCodCia = $dataRequest->enterpriseCode;
		$this->dataRequest->idProducto = $dataRequest->productCode;
		$this->dataRequest->dtfechorcargaIni = $dataRequest->initialDate;
		$this->dataRequest->dtfechorcargaFin = $dataRequest->finalDate;


		$response = $this->sendToService('callWs_StatusBulkReport');

		switch ($this->isResponseRc) {
			case 0:
				exportFile($response->archivo, $FileType, 'Estado_de_lote');
			break;
			default:
				$dataRequest->code = 0;
				$this->responseFail_DownloadFiles($dataRequest);
				$this->session->set_flashdata('download', $this->response);
				redirect(base_url(lang('CONF_LINK_STATUS_BULK')), 'Location', 302);
				exit;
		}


		return $this->responseToTheView('callWs_UnnmamedAffiliate');
	}

	public function callWs_RechargeMadeReport_DownloadFiles($dataRequest)
	{
		writeLog('INFO', 'DownloadFiles Model: RechargeMadeReport Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Reportes RecargasRealizadas';
		$this->dataAccessLog->operation = 'Descarga de archivos';

		switch ($dataRequest->type) {
			case lang('GEN_BTN_DOWN_XLS'):
				$this->dataRequest->idOperation = 'generarExcelRecargasRealizadas';
				$FileType = 'xls';
			break;
			case lang('GEN_BTN_DOWN_PDF'):
				$this->dataRequest->idOperation = 'generarPdfRecargasRealizadas';
				$FileType = 'pdf';
			break;
		}

    $fecha=$dataRequest->initialDatemy;
    $arreglo=explode ("/",$fecha);
    $mes=$arreglo[0];
    $anio=$arreglo[1];

		$this->dataRequest->className = 'com.novo.objects.TOs.RecargasRealizadasTO';
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->fecha = '';
		$this->dataRequest->fecha1 = '';
		$this->dataRequest->fecha2 = '';
		$this->dataRequest->accodcia = $dataRequest->enterpriseCode;
		$this->dataRequest->mesSeleccionado = $mes;
		$this->dataRequest->anoSeleccionado = $anio;

		$response = $this->sendToService('callWs_RechargeMadeReport');

		switch ($this->isResponseRc) {
			case 0:
				exportFile($response->archivo, $FileType, 'Recargas_realizadas');
			break;
			default:
				$dataRequest->code = 0;
				$this->responseFail_DownloadFiles($dataRequest);
				$this->session->set_flashdata('download', $this->response);
				redirect(base_url(lang('CONF_LINK_RECHARGE_MADE')), 'Location', 302);
				exit;
		}


		return $this->responseToTheView('callWs_UnnmamedAffiliate');
	}

	public function callWs_IssuedCardsReport_DownloadFiles($dataRequest)
	{
		writeLog('INFO', 'DownloadFiles Model: IssuedCardsReport Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Reportes TarjetasEmitidas';
		$this->dataAccessLog->operation = 'Descarga de archivos';

		switch ($dataRequest->type) {
			case lang('GEN_BTN_DOWN_XLS'):
				$this->dataRequest->idOperation = 'buscarTarjetasEmitidasExcel';
				$FileType = 'xls';
			break;
		}

		$this->dataRequest->className = 'com.novo.objects.MO.SaldosAmanecidosMO';
		$this->dataRequest->accodcia = $dataRequest->accodcia;
		$this->dataRequest->tipoConsulta = $dataRequest->radioButton;
		$this->dataRequest->idExtEmp = $dataRequest->acrif;
		$this->dataRequest->nombreEmpresa = $dataRequest->nameEnterprise;
		$this->dataRequest->fechaMes = $dataRequest->initialDatemy;

		$response = $this->sendToService('callWs_IssuedCardsReport');

		switch ($this->isResponseRc) {
			case 0:
				exportFile($response->archivo, $FileType, 'Tarjetas_emitidas');
			break;
			default:
				$dataRequest->code = 0;
				$this->responseFail_DownloadFiles($dataRequest);
				$this->session->set_flashdata('download', $this->response);
				redirect(base_url(lang('CONF_LINK_ISSUED_CARDS')), 'Location', 302);
				exit;
		}

		return $this->responseToTheView('callWs_UnnmamedAffiliate');
	}

	/**
	 * @info Arma respuesta en caso de falla de la descarga del archivo
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 06th, 2020
	 */
	public function responseFail_DownloadFiles($dataResponse)
	{
		writeLog('INFO', 'DownloadFiles Model: UnnmamedAffiliate Method Initialized');

		$this->response->code =  3;
		$this->response->title = lang('GEN_DOWNLOAD_FILE');
		$this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
		$this->response->icon =  lang('CONF_ICON_WARNING');
		$this->response->download =  TRUE;
		$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_ACCEPT');
		$this->response->modalBtn['btn1']['action'] = 'destroy';

		if ($dataResponse->code != 0) {
			$this->response->code =  $dataResponse->code;
			$this->response->title = $dataResponse->title;
			$this->response->msg = $dataResponse->msg;
			$this->response->icon =  $dataResponse->icon;
			$this->response->download =  FALSE;
			$this->response->modalBtn['btn1']['action'] = $dataResponse->modalBtn['btn1']['action'];
		}
	}
}
