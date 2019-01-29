<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//País
$config['country']='Co';

/*
|--------------------------------------------------------------------------
| Base CDN URL
|--------------------------------------------------------------------------
*/

//RUTA BASE PARA ARCHIVOS CDN  Ejemplo: https://cdn.novopayment.dev/empresas/Usd/
$config['base_url_cdn'] =  BASE_CDN_URL.$config['country'].'/';

//URL PARA CONECTAR POR SFTP A SERVIDOR
$config['URL_TEMPLOTES'] = 'sftp://172.24.6.102:22/u01/app/data/lotes/temp/'.$config['country'].'/';

//PATCH CARPETA DONDE SE SUBEN LOS LOTES Ejemplo: '/opt/httpd-2.4.4/vhost/cdn/empresas/Usd/bash/''
$config['FOLDER_UPLOAD_LOTES'] = '/opt/httpd-2.4.4/vhost/cdn/empresas/'.$config['country'].'/'.'bash/';

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
$config['language']	= 'ec-spanish';

/* End of file config.php */
/* Location: ./application/config/config.php */
