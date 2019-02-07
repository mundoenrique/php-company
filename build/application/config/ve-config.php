<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//País
$config['country']='Ve';

/*
|--------------------------------------------------------------------------
| Base CDN URL
|--------------------------------------------------------------------------
*/

//RUTA BASE PARA ARCHIVOS CDN  Ejemplo: https://cdn.novopayment.dev/empresas/Ve/
$config['base_url_cdn'] =  BASE_CDN_URL.$config['country'].'/';

//URL PARA CONECTAR POR SFTP A SERVIDOR
$config['URL_TEMPLOTES'] = URL_TEMPLOTES.$config['country'].'/';

//PATCH CARPETA DONDE SE SUBEN LOS LOTES Ejemplo: '/opt/httpd-2.4.4/vhost/cdn/empresas/Pe/bash/''
$config['FOLDER_UPLOAD_LOTES'] = FOLDER_UPLOAD_LOTES.$config['country'].'/'.'bash/';

//PATH CARPETA BASE CDN DEL PAÍS
$config['CDN'] = BASE_CDN_PATH.$config['country'].'/';

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
