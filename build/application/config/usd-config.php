<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//País
$config['country']='Usd';
$config['countryUri']='us';
$config['sess_expiration'] = 7200;

/*
|--------------------------------------------------------------------------
| Base CDN URL
|--------------------------------------------------------------------------
*/

//RUTA BASE PARA ARCHIVOS CDN  Ejemplo: https://cdn.novopayment.dev/empresas/Usd/
$config['base_url_cdn'] = ASSET_URL.$config['country'].'/';

//URL PARA CONECTAR POR SFTP A SERVIDOR
$config['URL_TEMPLOTES'] = BULK_FTP_URL.$config['country'].'/';

//PATCH CARPETA DONDE SE SUBEN LOS LOTES Ejemplo: '/opt/httpd-2.4.4/vhost/cdn/empresas/Co/bash/''
$config['FOLDER_UPLOAD_LOTES'] = ASSET_PATH.$config['country'].'/'.'bash/';

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
$config['language']	= 'usd-spanish';

/* End of file config.php */
/* Location: ./application/config/config.php */
