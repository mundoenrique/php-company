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
		log_message('INFO', 'NOVO DownloadFiles Model Class Initialized');
	}
	/**
	 * @info Elimina archivos descargados
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 06th, 2020
	 */
	public function callWs_DeleteFile_DownloadFiles($dataRequest)
	{
		log_message('INFO', 'NOVO DownloadFiles Model: DeleteFile Method Initialized');

		unlink(assetPath('downloads/'.$dataRequest->fileName));
		$this->response->code = 0;
		$this->response->data = '';
		return $this->responseToTheView('callWs_DeleteFile');
	}
	/**
	 * @info Elimina archivos descargados
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 06th, 2020
	 */
	public function callWs_UnnmamedAffiliate_DownloadFiles($dataRequest)
	{
		log_message('INFO', 'NOVO DownloadFiles Model: UnnmamedAffiliate Method Initialized');

		$this->className = 'com.novo.objects.MO.ListadoLotesMO';
		$this->dataAccessLog->modulo = 'lotes';
		$this->dataAccessLog->function = 'Innominadas';
		$this->dataAccessLog->operation = 'Detalle de lote';

		$this->dataRequest->idOperation = 'generarReporteTarjetasInnominadas';
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;
		$this->dataRequest->tarjetasInnominadas = [
			[
				'numLote' => $dataRequest->bulkNumber,
				'idExtEmp' => $this->session->enterpriseInf->idFiscal,
				'estatus' => '0',
				'enmascarar' => TRUE
			]
		];

		$response = $this->sendToService('callWs_unnmamedDetail');

		switch ($this->isResponseRc) {
			case 0:
				$fileInfo = json_decode($response->bean);
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
				redirect(base_url('detalle-innominadas'), 'location', 301);
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
		log_message('INFO', 'NOVO DownloadFiles Model: UnnmamedAffiliate Method Initialized');

		$this->response->code =  3;
		$this->response->title = lang('GEN_DOWNLOAD_FILE');
		$this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
		$this->response->icon =  lang('GEN_ICON_WARNING');
		$this->response->download =  TRUE;
		$this->response->data->resp['btn1']['text'] = lang('GEN_BTN_ACCEPT');
		$this->response->data->resp['btn1']['action'] = 'close';

		if ($dataResponse->code != 0) {
			$this->response->code =  $dataResponse->code;
			$this->response->title = $dataResponse->title;
			$this->response->msg = $dataResponse->msg;
			$this->response->icon =  $dataResponse->icon;
			$this->response->download =  FALSE;
			$this->response->data->resp['btn1']['action'] = $dataResponse->data->resp['btn1']['action'];
		}
	}
}
