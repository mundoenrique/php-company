<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//País
$config['country']='Ve';
$config['countryUri']='ve';
$config['sess_expiration'] = 7200;

/*
|--------------------------------------------------------------------------
| Base CDN URL
|--------------------------------------------------------------------------
*/

//RUTA BASE PARA ARCHIVOS CDN  Ejemplo: https://cdn.novopayment.dev/empresas/Ve/
$config['base_url_cdn'] = ASSET_URL.$config['country'].'/';

//PATH CARPETA BASE CDN DEL PAÍS
$config['CDN'] = ASSET_PATH.$config['country'].'/';

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
$config['language']	= 've-spanish';

/* End of file config.php */
/* Location: ./application/config/config.php */
