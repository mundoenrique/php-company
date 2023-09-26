<?php
defined('BASEPATH') or exit('No direct script access allowed');

//País
$config['country'] = 'Ec-bp';
$config['countryUri'] = 'bpi';
$config['countryUri'] = in_array('bpi', CUSTUMER_DENY_WAY, TRUE) ? 'bpi' : 'bp';
$config['sess_expiration'] = 7200;
$config['client'] = 'pichincha';
/*
|--------------------------------------------------------------------------
| Base CDN URL
|--------------------------------------------------------------------------
*/

//RUTA BASE PARA ARCHIVOS CDN  Ejemplo: https://cdn.novopayment.dev/empresas/Usd/
$config['base_url_cdn'] =  ASSET_URL . $config['country'] . '/';

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
$config['language'] = 'ec-bp-spanish';

/* End of file config.php */
/* Location: ./application/config/config.php */
