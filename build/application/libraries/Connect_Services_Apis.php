<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Libreria para la comunicación con los servicios y APIs
 * @author J. Enrique Peñaloza Piñero
 */
class Connect_Services_Apis
{
  private $CI;

  public function __construct()
  {
    writeLog('INFO', 'Connect_Services_Apis Library Class Initialized');

    $this->CI = &get_instance();
  }

  public function connectWebServices($request)
  {
    writeLog('INFO', 'Connect_Services_Apis: connectWebLogicservices Method Initialized');

    if (ENVIRONMENT === 'development') {
      error_reporting(E_ALL & ~E_DEPRECATED);
    }

    $subFix = '_' . strtoupper($this->CI->config->item('customer_uri'));
    $wsUrl = $_SERVER['WS_URL'];

    if (isset($_SERVER['WS_URL' . $subFix])) {
      $wsUrl = $_SERVER['WS_URL' . $subFix];
    }

    writeLog('DEBUG', 'REQUEST BY WEB SERVICE URL: ' . $wsUrl);

    $method = $request->method ?? 'POST';
    unset($request->method);
    $requestSerV = json_encode($request, JSON_UNESCAPED_UNICODE);
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $wsUrl,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_ENCODING => 'gzip, deflate, br',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 58,
      CURLOPT_FOLLOWLOCATION => TRUE,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => [
        'Content-Type: text/plain',
        'Content-Length: ' . strlen($requestSerV)
      ]
    ]);

    if ($method !== 'GET') {
      curl_setopt($curl, CURLOPT_POSTFIELDS, $requestSerV);
    }

    $curlResp = curl_exec($curl);
    $executionTime = curl_getinfo($curl, CURLINFO_TOTAL_TIME);

    $response = new stdClass();
    $response->HttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $response->code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    $response->data = $curlResp;
    $response->errorNo = (int) curl_errno($curl);
    $response->error = curl_error($curl);

    if ($response->HttpCode !== 200 || !$curlResp) {
      $response->code = lang('SETT_RC_DEFAULT');
    }

    curl_close($curl);
    $executionTime = round($executionTime, 2, PHP_ROUND_HALF_UP);

    writeLog('DEBUG', 'RESPONSE IN ' . $executionTime . ' SEC, CURL HTTP CODE: ' . $response->HttpCode);

    if ($response->errorNo !== 0) {
      writeLog('ERROR', 'CURL ERROR NUMBER: ' . $response->errorNo . ', ERROR MESSAGE: ' . $response->error);
    }

    $tempData = json_decode($response->data);
    $typeData = gettype($tempData);

    if ($typeData === 'object' || $typeData === 'array') {
      $response->data = $tempData;
    }

    return responseServer($response);
  }

  public function moveFileToWebService($file)
  {
    writeLog('INFO', 'Connect_Services_Apis: moveFileToWebService Method Initialized');

    $urlBulkService = BULK_FTP_URL;
    $userpassBulk =  BULK_FTP_USERNAME . ':' . BULK_FTP_PASSWORD;

    writeLog('DEBUG', 'UPLOAD FILE TO: ' . $urlBulkService . $file);

    $connect = ssh2_connect($urlBulkService, 22);
    if (!$connect) {
      writeLog('ERROR', 'Could not connect to the SFTP server.');
      return responseServer(['HttpCode' => 100, 'code' => -105, 'errorNo' => -1, 'error' => 'Could not connect to the SFTP server.']);
    }

    $privateKey = file_get_contents('/var/www/key/id_rsa_docker_dtu');
    if (!ssh2_auth_pubkey_add($connect, BULK_FTP_USERNAME, $privateKey, '')) {
      writeLog('ERROR', 'Could not authenticate with the SFTP server.');
      return responseServer(['HttpCode' => 100, 'code' => -105, 'errorNo' => -1, 'error' => 'Could not authenticate with the SFTP server.']);
    }

    $sftp = ssh2_sftp($connect);
    $stream = fopen('ssh2.sftp://' . $sftp . '/sftp_data/DEV/Archivos_lote' . $this->CI->config->item('customer') . '/' . $file, 'w');

    $localFile = fopen(UPLOAD_PATH . $file, 'r');
    stream_copy_to_stream($localFile, $stream);

    fclose($localFile);
    fclose($stream);
    unlink(UPLOAD_PATH . $file);

    $response = new stdClass();
    $response->HttpCode = 200;
    $response->code = 0;
    $response->data = new stdClass();
    $response->errorNo = 0;
    $response->error = '';

    //writeLog('DEBUG', 'RESPONSE IN '. $executionTime. ' SEC, UPLOAD FILE RESPONSE CODE: '. $response->code. $msg);

    return responseServer($response);
  }

  public function connectMfaServices($request)
  {
    writeLog('INFO', 'Connect_Services_Apis: connectMfaServices Method Initialized');

    if (API_GEE_WAY && !$this->CI->session->tempdata('jwtOauth')) {
      $this->getJwtOauthApiGee();
    }

    $urlMfaServ = URL_MFA_SERV . $request->uri;
    $method = $request->method ?? 'POST';
    unset($request->method);
    $uuIdV4 = uuIdV4Generate();

    writeLog('DEBUG', 'REQUEST BY MFA SERVICE URL: ' . $urlMfaServ . ', UUID: ' . $uuIdV4);

    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $urlMfaServ,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_ENCODING => 'gzip, deflate, br',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 58,
      CURLOPT_FOLLOWLOCATION => TRUE,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => [
        'Content-Type: application/json; charset=utf-8',
        'accept: application/json; charset=utf-8',
        'X-Request-Id: ' . $uuIdV4,
        'X-Tenant-Id: pe-servitebca',
      ],
    ]);

    if ($method !== 'GET') {
      curl_setopt($curl, CURLOPT_POSTFIELDS, $request->requestBody);
    }

    $curlResp = json_decode(curl_exec($curl));
    $executionTime = curl_getinfo($curl, CURLINFO_TOTAL_TIME);

    $response = new stdClass();
    $response->HttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $response->code = isset($curlResp->code) ? $curlResp->code : lang('SETT_RC_DEFAULT');
    $response->datetime = isset($curlResp->datetime) ? $curlResp->datetime : '';
    $response->message = isset($curlResp->message) ? $curlResp->message : '';
    $response->data = isset($curlResp->data) ? $curlResp->data : NULL;
    $response->error = curl_error($curl);
    $response->errorNo = (int) curl_errno($curl);

    curl_close($curl);
    $executionTime = round($executionTime, 2, PHP_ROUND_HALF_UP);

    writeLog('DEBUG', 'RESPONSE IN ' . $executionTime . ' SEC, CURL HTTPCODE: ' . $response->HttpCode .
      ', SERVICE CODE: ' . $response->code . ' ' . $response->message);

    if ($response->errorNo !== 0) {
      writeLog('ERROR', 'CURL ERROR NUMBER: ' . $response->errorNo . ', ERROR MESSAGE: ' . $response->error);
    }

    return responseServer($response);
  }

  public function getJwtOauthApiGee()
  {
    writeLog('INFO', 'Connect_Services_Apis: getJwtOauthApiGee Method Initialized');

    $urlApiGeeHost = URL_APIGEE_OAUTH;
    $clientIdApiGee = CLIENT_ID_APIGEE;
    $ClientSecretApigee = CLIENT_SECRET_APIGEE;

    writeLog('DEBUG', 'CORE_SERVICE URL: ' . $urlApiGeeHost);

    $startReq = microtime(true);
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $urlApiGeeHost,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_ENCODING => 'gzip, deflate, br',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 58,
      CURLOPT_FOLLOWLOCATION => TRUE,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => [
        'Content-type: application/x-www-form-urlencoded; charset=utf-8',
        'accept: application/json; charset=utf-8',
      ],
      CURLOPT_POSTFIELDS => http_build_query([
        'grant_type' => 'client_credentials',
        'client_id' => $clientIdApiGee,
        'client_secret' => $ClientSecretApigee
      ]),
    ]);

    $curlResponse = curl_exec($curl);
    $curlHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlErrorNo = curl_errno($curl);
    $curlError = curl_error($curl);

    curl_close($curl);
    $finalReq = microtime(true);
    $executionTime = round($finalReq - $startReq, 2, PHP_ROUND_HALF_UP);

    writeLog('DEBUG', 'RESPONSE IN ' . $executionTime . ' sec CURL HTTP CODE: ' . $curlHttpCode);

    $curlResponse = json_decode($curlResponse);

    if ($curlHttpCode !== 200 || !$curlResponse) {
      switch ($curlErrorNo) {
        case 28:
          $curlResponse->rc = 504;
          break;
        default:
          $curlResponse->rc = lang('SETT_RC_DEFAULT');
      }

      switch ($curlHttpCode) {
        case 502:
          $curlResponse->rc = 502;
          break;
      }
    } else {
      $curlResponse->rc = 0;
      $this->CI->session->set_tempdata('jwtOauth', $curlResponse->access_token, 1860);
    }

    return $curlResponse;
  }
}
