<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//País
$config['country']='Ve';

//CLIENT ID OAUTH
$config['client_id'] = '1923eba01b1d3667535cd2e186fea727';

//CLIENT SECRET OAUTH
$config['client_secret'] = 'c3aad9624f45bcd0f10bacafe026271c';

//URL API CONTENT
$config['urlAPIContent'] = '172.24.6.123:3000/';

/*
|--------------------------------------------------------------------------
| Base CDN URL
|--------------------------------------------------------------------------
*/

//RUTA BASE PARA ARCHIVOS CDN  Ejemplo: https://cdn.novopayment.dev/empresas/Ve/
$config['base_url_cdn'] =  BASE_CDN_URL.$config['country'].'/';

//USUARIO Y PASSWORD PARA SFTP PASO DE LOTES
$config['LOTES_USERPASS'] = 'npdaemon:#.3Nv!!';

//URL PARA CONECTAR POR SFTP A SERVIDOR
$config['URL_TEMPLOTES'] = 'sftp://172.24.6.130:22/home/npdaemon/'.$config['country'].'/';

//PATCH CARPETA DONDE SE SUBEN LOS LOTES Ejemplo: '/opt/httpd-2.4.4/vhost/cdn/empresas/Ve/bash/''
$config['FOLDER_UPLOAD_LOTES'] = '/opt/httpd-2.4.4/vhost/cdn/empresas/'.$config['system_name'].'/'.$config['country'].'/'.'bash/';

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
