<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Libreria para la inccorporación y versionamiento de los archivos css, js e imágenes
 * @author J. Enrique Peñaloza Piñero
 *
 */
class Asset {
	private $cssFiles = [];
	private $jsFiles = [];

	public function __construct()
	{
		log_message('INFO', 'NOVO Assets Library Class Initialized');
	}
	/**
	 * @info Método para inicializar los atributos de la librería
	 * @author: J. Enrique Peñaloza P.
	 */
	public function initialize($params = [])
	{
		log_message('INFO', 'NOVO Asset: initialize method initialized');
		foreach($params as $arrayFiles => $file) {
			isset($this->$arrayFiles) ? $this->$arrayFiles = $file : '';
		}
	}
	/**
	 * @info Método para insertar archivos css en el documento
	 * @author: J. Enrique Peñaloza P.
	 */
	public function insertCss()
	{
		log_message('INFO', 'NOVO Assets: insertCss method initialized');
		$file_url = NULL;
		foreach($this->cssFiles as $fileName) {
			$file = assetPath('css/'.$fileName.'.css');
			$file = $this->versionFiles($file, $fileName, '.css');
			$file_url .= '<link rel="stylesheet" href="'.assetUrl('css/'.$file).'"/>'.PHP_EOL;
		}
		return $file_url;
	}
	/**
	 * @info Método para insertar archivos js en el documento
	 * @author J. Enrique Peñaloza P.
	 */
	public function insertJs()
	{
		log_message('INFO', 'NOVO Assets: insertJs method initialized');
		$file_url = NULL;
		foreach($this->jsFiles as $fileName) {
			$file = assetPath('js/'.$fileName.'.js');
			$file = $this->versionFiles($file, $fileName, '.js');
			$file_url .= '<script src="'.assetUrl('js/'.$file).'"></script>'.PHP_EOL;
		}
		return $file_url;
	}
	/**
	 * @info Método para insertar imagenes, json, etc
	 * @author J. Enrique Peñaloza P.
	 */
	public function insertFile($fileName, $folder = 'images', $country = FALSE)
	{
		log_message('INFO', 'NOVO Assets: insertFile method initialized');
		$country = $country ? $country.'/' : '';
		$file = assetPath($folder.'/'.$country.$fileName);
		$version = '?V'.date('Ymd-U', filemtime($file));
		return assetUrl($folder.'/'.$country.$fileName.$version);
	}
	/**
	 * @info Método para versionar archivos
	 * @author J. Enrique Peñaloza P.
	 */
	private function versionFiles($file, $fileName, $ext)
	{
		$version = '';
		$thirdParty = strpos($fileName, 'third_party');
		if($thirdParty === FALSE && file_exists($file)) {
			$version = '?V'.date('Ymd-U', filemtime($file));
			//$ext = (ENVIRONMENT === 'testing' || ENVIRONMENT === 'production') ? '.min'.$ext : $ext;
		} else {
			$ext = '.min'.$ext;
		}
		return $fileName.$ext.$version;
	}
	/**
	 * @info Verifica la existencia de un archivo
	 * @autor Pedro Torres
	 * @date 23/09/2019
	 */
	public function verifyFileUrl($url)
	{
		return @get_headers($url)[0] === 'HTTP/1.1 200 OK';
	}
}
