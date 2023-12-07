<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Clase Modelo de Conexión Empresas Online (CEO)
 *
 * Esta clase es la súper clase de la que heredarán todos los modelos
 * de la aplicación.
 *
 * @package models
 * @author J. Enrique Peñaloza Piñero
 * @date May 16th, 2020
 */
class NOVO_Model extends CI_Model
{
  public $dataAccessLog;
  public $accessLog;
  public $customer;
  public $customerUri;
  public $customerFiles;
  public $dataRequest;
  public $userName;
  public $autoLogin;
  public $token;
  public $isResponseRc;
  public $response;

  public function __construct()
  {
    parent::__construct();
    writeLog('INFO', 'Model Class Initialized');

    $this->dataAccessLog = new stdClass();
    $this->customer = $this->session->customerSess ?? $this->config->item('customer');
    $this->customerUri = $this->session->customerUri ?? $this->config->item('customer_uri');
    $this->customerFiles = $this->config->item('customer_files');
    $this->dataRequest = new stdClass();
    $this->userName = $this->session->userName;
    $this->autoLogin = $this->session->autoLogin ?? '';
    $this->token = $this->session->token ?? '';
    $this->response = new stdClass();
    $this->response->code = lang('SETT_DEFAULT_CODE');
    $this->response->icon = lang('SETT_ICON_WARNING');
    $this->response->title = lang('GEN_SYSTEM_NAME');
    $this->response->msg = '';
    $this->response->data = new stdClass();
    $this->response->modalBtn = [];
  }
  /**
   * @info Método para comunicación con el servicio
   * @author J. Enrique Peñaloza Piñero.
   * @date April 20th, 2019
   */
  public function sendToWebServices($model)
  {
    writeLog('INFO', 'Model: sendToWebServices Method Initialized');

    $request = [];
    $this->accessLog = accessLog($this->dataAccessLog);

    $this->dataRequest->pais = $this->customer;
    $this->dataRequest->token = $this->token;
    $this->dataRequest->autoLogin = $this->autoLogin;

    if (lang('SETT_AGENT_INFO') === 'ON') {
      $this->dataRequest->aplicacion = $this->session->enterpriseInf->thirdApp ?? '';
      $this->dataRequest->dispositivo = $this->agent->is_mobile() ? 'mobile' : 'desktop';
      $this->dataRequest->marca = $this->agent->is_mobile() ? $this->agent->mobile() : '';
      $this->dataRequest->navegador = $this->agent->browser() . ' V-' . floatval($this->agent->version());
    }

    $this->dataRequest->logAccesoObject = $this->accessLog;
    $request['bean'] = $this->dataRequest;
    $request['pais'] = $this->customer;
    $dataRequest = json_encode($this->dataRequest, JSON_UNESCAPED_UNICODE);

    writeLog('DEBUG', 'WEB SERVICES REQUEST ' . $model . ': ' . json_encode($request, JSON_UNESCAPED_UNICODE));

    $encryptRequest = $this->encrypt_decrypt->encryptWebServices($dataRequest);
    $request['bean'] = $encryptRequest;
    $encryptResponse = $this->connect_services_apis->connectWebServices($request);
    $response = $this->encrypt_decrypt->decryptWebServices($encryptResponse);
    $response = handleResponseServer($response);
    $logResponse = handleLogResponse($response);

    writeLog('DEBUG', 'WEB SERVICES RESPONSE ' . $model . ': ' . json_encode($logResponse, JSON_UNESCAPED_UNICODE));

    unset($logResponse);

    return $this->makeAnswer($response, $model);
  }

  /**
   * @info Método para comunicación con el servicio
   * @author J. Enrique Peñaloza Piñero.
   * @date April 20th, 2019
   */
  public function sendFile($file, $model)
  {
    writeLog('INFO', 'Model: sendFile Method Initialized');

    $responseUpload = $this->connect_services_apis->moveFileToWebService($file, $model);
    $responseUpload = handleResponseServer($responseUpload);
    $logResponse = handleLogResponse($responseUpload);

    writeLog('DEBUG', 'SFTP SERVICE RESPONSE ' . $model . ': ' . json_encode($logResponse, JSON_UNESCAPED_UNICODE));

    unset($logResponse);

    return $this->makeAnswer($responseUpload, $model);
  }
  /**
   * @info Método armar la respuesta a los modelos
   * @author J. Enrique Peñaloza Piñero.
   * @date December 11th, 2019
   */
  protected function makeAnswer($responseModel, $model)
  {
    writeLog('INFO', 'Model: makeAnswer Method Initialized');

    $responseCode = $responseModel->rc ?? $responseModel->responseCode;
    $this->isResponseRc = (int) $responseCode;
    $linkredirect = uriRedirect();

    $arrayResponse = [
      'btn1' => [
        'text' => lang('GEN_BTN_ACCEPT'),
        'link' => $linkredirect,
        'action' => 'redirect'
      ]
    ];

    switch ($this->isResponseRc) {
      case -29:
      case -61:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->msg = lang('GEN_DUPLICATED_SESSION');
        clearSessionsVars();
        break;
      case -134:
        $this->response->msg = ENVIRONMENT !== 'production' ? lang('GEN_NOT_DECRYPTED') : lang('GEN_SYSTEM_MESSAGE');
        break;
      case -259:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->msg = lang('GEN_WITHOUT_AUTHORIZATION');
        break;
      case -437:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->msg = novoLang(lang('GEN_FAILED_THIRD_PARTY'), '');
        break;
      case 502:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->msg = lang('GEN_SYSTEM_MESSAGE');
        clearSessionsVars();
        break;
      case 504:
        $this->response->msg = lang('GEN_TIMEOUT');
        break;
      default:
        $this->response->msg = lang('GEN_SYSTEM_MESSAGE');
        break;
    }

    $this->response->msg = $this->isResponseRc === 0 ? lang('GEN_MSG_RC_0') : $this->response->msg;
    $this->response->modalBtn = $arrayResponse;

    return $responseModel->data ?? $responseModel;
  }
  /**
   * @info Método enviar el resultado de la consulta a la vista
   * @author J. Enrique Peñaloza Piñero.
   * @date November 21st, 2019
   */
  public function responseToTheView($model)
  {
    writeLog('INFO', 'Model: responseToView Method Initialized');
    $responsetoView = new stdClass();

    foreach ($this->response as $pos => $response) {
      if ($pos === 'data' && is_object($response)) {
        $responsetoView->$pos = new stdClass();

        foreach ($response as $key => $value) {
          if ($key === 'file') {
            continue;
          }

          $responsetoView->$pos->$key = $value;
        }

        continue;
      }

      $responsetoView->$pos = $response;
    }

    writeLog('DEBUG', 'RESULT ' . $model . ' SENT TO THE VIEW ' . json_encode($responsetoView, JSON_UNESCAPED_UNICODE));

    unset($responsetoView);

    return $this->response;
  }
}
