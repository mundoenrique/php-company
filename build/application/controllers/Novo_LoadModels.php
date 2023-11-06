<?php
defined('BASEPATH') or exit('No direct script access alloweb');

/**
 * @info Controlador para las peticiones asíncronas de la aplicación
 * @author J. Enrique Peñaloza Piñero
 * @date April 20th, 2019
 */
class Novo_LoadModels extends NOVO_Controller
{
  public function __construct()
  {
    parent::__construct();
    writeLog('INFO', 'LoadModels Controller Class Initialized');

    if (!$this->input->is_ajax_request()) {
      redirect('page-no-found', 'Location', 301);
    }
  }

  /**
   * @info Método que valida y maneja las peticiones asincornas de la aplicación
   * @author J. Enrique Peñaloza Piñero
   * @date April 20th, 2019
   */
  public function index()
  {
    writeLog('INFO', 'LoadModels: index Method Initialized');

    if ($this->isValidRequest) {
      $this->dataResponse = $this->loadModel($this->request);
    } else {
      $this->dataResponse = $this->verify_access->responseByDefect();
    }

    $customerData = encryptData($this->dataResponse);
    $this->output->set_content_type('application/json')->set_output($customerData);
  }
}
