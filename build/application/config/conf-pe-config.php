<?php
defined('BASEPATH') or exit('No direct script access allowed');

//País
$config['country'] = 'Pe';
$config['countryUri'] = in_array('per', CUSTUMER_DENY_WAY, TRUE) ? 'per' : 'pe';
$config['sess_expiration'] = 7200;

/*
|--------------------------------------------------------------------------
| Base CDN URL
|--------------------------------------------------------------------------
*/

//RUTA BASE PARA ARCHIVOS CDN  Ejemplo: https://cdn.novopayment.dev/empresas/Pe/
$config['base_url_cdn'] = ASSET_URL . $config['country'] . '/';

//PATH CARPETA BASE CDN DEL PAÍS
$config['CDN'] = ASSET_PATH . $config['country'] . '/';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language'] = 'pe-spanish';

/* End of file config.php */
/* Location: ./application/config/config.php */
