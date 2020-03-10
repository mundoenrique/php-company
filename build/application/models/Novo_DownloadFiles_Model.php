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
		return $this->responseToTheView('DeleteFile');
	}
}
