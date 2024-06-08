<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * NOVOPAYMENT server Helpers
 *
 * @subpackage  Helpers
 * @category    Helpers
 * @author      J. Enrique Peñaloza P
 * @date        September 19th, 2022
 */

if (!function_exists('writeLog')) {
  function writeLog($level, $message)
  {
    $CI = &get_instance();
    $ip = $CI->input->ip_address();
    $level = mb_strtoupper($level);

    if ($level !== 'INFO') {
      $customer = $CI->session->customerSess ?? $CI->config->item('customer');
      $isLogUser = $CI->session->has_userdata('logUser');
      $logUser = $CI->session->logUser ?? date('U') . '-';
      list($date, $user) = explode('-', $logUser);
      $reqUser = $user;

      if ($CI->session->has_userdata('userName')) {
        $reqUser = $CI->session->userName;
      } elseif ($CI->input->get_post('userName') !== NULL) {
        $reqUser = mb_strtoupper($CI->input->get_post('userName'));
      } elseif ($CI->input->get_post('user-name') !== NULL) {
        $reqUser = mb_strtoupper($CI->input->get_post('user-name'));
      } elseif ($CI->input->get_post('email') !== NULL) {
        $reqUser = mb_strtoupper($CI->input->get_post('email'));
      }

      if (!$isLogUser || $user !== $reqUser) {
        $logUser = $date . '-' . $reqUser;
        $CI->session->set_userdata('logUser', $logUser);
      }

      $message = novoLang('NOVO [%s] IP: %s, CUSTOMER: %s, %s', [$logUser, $ip, $customer, $message]);
    } else {
      $message = novoLang('NOVO %s', $message);
    }

    log_message($level, $message);
  }
}

// generar uuIdV4
if (!function_exists('uuIdV4Generate')) {
  function uuIdV4Generate()
  {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }
}

// Maneja respuesta del servicio
if (!function_exists('responseServer')) {
  function responseServer($responseServer)
  {
    $code = explode('.', $responseServer->code);

    switch ($responseServer->HttpCode) {
      case 200:
        $responseServer->responseCode =  $responseServer->data->rc ?? 0;
        break;

      case 502:
        $responseServer->responseCode = 502;
        break;

      case 504:
        $responseServer->responseCode = 504;
        break;

      default:
        $responseServer->responseCode = $code[2] ?? $code[0];
    }

    if ($responseServer->errorNo === 28) {
      $responseServer->responseCode = 504;
    }

    return $responseServer;
  }
}

if (!function_exists('handleResponseServer')) {
  function handleResponseServer($webServiceResp)
  {
    if (isset($webServiceResp->data->rc)) {
      $webServiceResp->responseCode = $webServiceResp->data->rc;
    }

    if (isset($webServiceResp->data->logAcceso)) {
      $accessLog = $webServiceResp->data->logAcceso;
      $accessLog =  gettype($accessLog) === 'string' ? json_decode($accessLog) : $accessLog;

      if (gettype($accessLog) === 'object' || gettype($accessLog) === 'array') {
        $webServiceResp->data->logAcceso = $accessLog;
      }
    }

    if (isset($webServiceResp->data->archivo)) {
      $webServiceResp->binaryFile = 'success';

      if (!is_array($webServiceResp->data->archivo)  || empty($webServiceResp->data->archivo)) {
        $webServiceResp->binaryFile = 'No binary array';
      }
    }

    if (isset($webServiceResp->data->bean)) {
      $bean = $webServiceResp->data->bean;
      $bean =  gettype($bean) === 'string' ? json_decode($bean) : $bean;

      if (gettype($bean) === 'object' || gettype($bean) === 'array') {
        $webServiceResp->data->bean = $bean;
      }

      if (isset($webServiceResp->data->bean->archivo)) {
        $webServiceResp->binaryFile = 'success';

        if (!is_array($webServiceResp->data->bean->archivo) || empty($webServiceResp->data->bean->archivo)) {
          $webServiceResp->binaryFile = 'No binary array';
        }
      }
    }

    return $webServiceResp;
  }
}

if (!function_exists('handleLogResponse')) {
  function handleLogResponse($responseToLog)
  {
    $logResponse = new stdClass();

    foreach ($responseToLog as $pos => $data) {
      if ($pos === 'data' && (gettype($data) === 'object'  || gettype($data) === 'array')) {
        $logResponse->data = new stdClass();

        foreach ($data as $key => $value) {
          if ($key === 'archivo') {
            continue;
          }

          if ($key === 'bean' && (gettype($value) === 'object'  || gettype($value) === 'array')) {
            $logResponse->data->bean = new stdClass();

            foreach ($value as $index => $content) {
              if ($index === 'archivo') {
                continue;
              }

              $logResponse->data->bean->$index = $content;
            }

            continue;
          }

          $logResponse->data->$key = $value;
        }

        continue;
      }

      $logResponse->$pos = $data;
    }

    return $logResponse;
  }
}
