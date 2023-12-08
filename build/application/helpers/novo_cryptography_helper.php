<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Novopayment cryptography Helpers
 *
 * @package CodeIgniter
 * @subpackage Helpers
 * @category Helpers
 * @author desarrolloweb@novopayment.com
 * @date October 1st, 2022
 */
if (!function_exists('decryptData')) {
  /**
   * @info decrypt data
   * @author epenaloza
   * @date October 3rd, 2022
   * @param json $requestData data to decrypt
   * @param bool $external externaL request
   * @return object|array|string data decrypted
   */
  function decryptData($requestData, $external = FALSE)
  {
    $CI = &get_instance();

    if (ACTIVE_SAFETY && !$external) {
      $req = json_decode(base64_decode($requestData));
      $requestData = $CI->cryptography->decrypt(base64_decode($req->plot), utf8_encode($req->data));
    } else {
      $requestData = $requestData;
    }

    return xss_clean(strip_tags($requestData));
  }
}

if (!function_exists('encryptData')) {
  /**
   * @info decrypt data
   * @author epenaloza
   * @date October 3rd, 2022
   * @param object|array|string $responseData data to encrypt
   * @return json data encrypted
   */
  function encryptData($responseData)
  {
    $CI = &get_instance();
    $responseData->redirectLink = uriRedirect();
    $responseData->logged = $CI->session->has_userdata('logged');
    $responseData->userId = $CI->session->has_userdata('userId');

    $responseData = [
      'payload' => $responseData
    ];

    if (ACTIVE_SAFETY) {
      $responseData['payload']->novoName = $CI->security->get_csrf_token_name();
      $responseData['payload']->novoValue = $CI->security->get_csrf_hash();
      $responseData['payload'] = base64_encode(
        json_encode($CI->cryptography->encrypt($responseData['payload']), JSON_UNESCAPED_UNICODE)
      );
    }

    return json_encode($responseData, JSON_UNESCAPED_UNICODE);
  }
}
