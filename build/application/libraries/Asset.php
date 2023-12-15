<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Libreria para la inccorporación y versionamiento de los archivos css, js e imágenes
 * @author J. Enrique Peñaloza Piñero
 *
 */
class Asset
{
  private $cssFiles;
  private $jsFiles;
  private $CI;

  public function __construct()
  {
    writeLog('INFO', 'Asset Library Class Initialized');

    $this->cssFiles = [];
    $this->jsFiles = [];
    $this->CI = &get_instance();
    $_SERVER['REMOTE_ADDR'] = $this->CI->input->ip_address();
  }
  /**
   * @info Método para inicializar los atributos de la librería Asset
   * @author: J. Enrique Peñaloza Piñero.
   * @param string[] $params
   * @return void
   */
  public function initialize($params = [])
  {
    writeLog('INFO', 'Asset: initialize method initialized');

    foreach ($params as $arrayFiles => $file) {
      isset($this->$arrayFiles) ? $this->$arrayFiles = $file : '';
    }
  }
  /**
   * @info Método para insertar archivos css en el documento html
   * @author: J. Enrique Peñaloza Piñero.
   * @param void
   * @return string
   */
  public function insertCss()
  {
    writeLog('INFO', 'Asset: insertCss method initialized');

    $link = NULL;
    $FileExt = 'css';

    foreach ($this->cssFiles as $fileName) {
      $FilePath = assetPath('css/' . $fileName);
      $fileVersion = $this->versionFiles($FilePath, $fileName, $FileExt);
      $link .= '<link rel="stylesheet" href="' . assetUrl('css/' . $fileVersion) . '" media="all">' . PHP_EOL;
    }

    return $link;
  }
  /**
   * @info Método para insertar archivos js en el documento html
   * @author J. Enrique Peñaloza Piñero.
   * @param void
   * @return string
   */
  public function insertJs($wasMigrated)
  {
    writeLog('INFO', 'Asset: insertJs method initialized');

    $script = NULL;
    $fileExt = 'js';
    $type = 'text/javascript';

    foreach ($this->jsFiles as $fileName) {
      $filePath = assetPath('js/' . $fileName);
      $fileVersion = $this->versionFiles($filePath, $fileName, $fileExt);

      if ($wasMigrated) {
        $type = strpos($filePath, 'third_party') !== FALSE ? 'text/javascript' : 'module';
      }

      $script .= '<script type="' . $type . '" defer src="' . assetUrl('js/' . $fileVersion) . '"></script>' . PHP_EOL;
    }

    return $script;
  }
  /**
   * @info Método para insertar imágenes, json, pdf, videos, etc, en el documento hmlt
   * @author J. Enrique Peñaloza Piñero.
   * @param string $file
   * @param string $location
   * @param string $customerFiles
   * @param string $folder
   * @return string
   */
  public function insertFile($file, $location, $customerFiles = '', $folder = '')
  {
    writeLog('INFO', 'Asset: insertFile method initialized');

    $location .= '/';
    $customerFiles = !empty($customerFiles) ? $customerFiles . '/' : $customerFiles;
    $folder = !empty($folder) ? $folder . '/' : $folder;
    list($fileName, $fileExt) = explode('.', $file);
    $filePath = assetPath($location . $customerFiles . $folder . $fileName);

    if (strpos($location, 'images') !== FALSE && !file_exists($filePath . '.' . $fileExt)) {
      $customerFiles = 'default/';
      $filePath = assetPath($location . $customerFiles . $folder . $fileName);
    }

    $fileVersion = $this->versionFiles($filePath, $fileName, $fileExt);

    return assetUrl($location . $customerFiles . $folder . $fileVersion);
  }
  /**
   * @info Método para versionar archivos en el documento hmlt
   * @author J. Enrique Peñaloza Piñero.
   * @param string $file
   * @param string $fileName
   * @param string $fileExt
   * @return string
   */
  private function versionFiles($file, $fileName, $fileExt)
  {
    $version = '';
    $thirdParty = strpos($file, 'third_party');
    $fileExt = $thirdParty ? '.min.' . $fileExt : '.' . $fileExt;
    $file = $file . $fileExt;
    $fileExists = file_exists($file);

    if (!$fileExists) {
      writeLog('ERROR', 'Required file ' . $file);
    }

    if (!$thirdParty) {
      $version = '?V' . date('Ymd-U', filemtime($file));
    }

    return $fileName . $fileExt . $version;
  }
}
