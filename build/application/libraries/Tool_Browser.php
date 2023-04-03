<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Libreria para validar el navegador
 * @author J. Enrique Peñaloza Piñero
 * @date January 24th, 2020
 */
class Tool_Browser {
	private $CI;

	public function __construct()
	{
		log_message('INFO', 'NOVO Tool_Browser Library Class Initialized');
		$this->CI = &get_instance();
	}
	/**
	 * @info Método para determinar que el navegador es compatible con la aplicación
	 * @author J. Enrique Peñaloza Piñero.
	 * @date January 24th, 2020
	 */
	public function validBrowser($client)
	{
		log_message('INFO', 'NOVO Tool_Browser: validBrowser Method Initialized');

		$this->CI->load->library('user_agent');
		$valid = FALSE;
		$platform = 'Unidentified';
		$browsersIn = FALSE;

		if($this->CI->agent->is_browser()) {
			$platform = 'browser';
			$validBrowser = [
				'Chrome' => 47,
				'Firefox' => 29,
				'Opera' => 34,
				'Safari' => 9,
				'Edge' => 13,
				'Internet Explorer' => 11
			];

			$browser = $this->CI->agent->browser();
			$version = floatval($this->CI->agent->version());

			if(array_key_exists($browser, $validBrowser)) {
				if(lang('CONF_SUPPORT_IE') == 'ON') {
					$validBrowser['Internet Explorer'] = 10;
				}

				log_message('DEBUG', 'NOVO validBrowser: browser access '.$browser.' version '.$version);

				$browsersIn = TRUE;
				$valid = $version > $validBrowser[$browser];

				if($valid && $browser === 'Internet Explorer') {
					$valid = 'ie11';
				}
			}
		}

		if($this->CI->agent->is_mobile()) {
			$platform = 'mobile';
		}

		if($this->CI->agent->is_robot()) {
			$platform = 'robot';
		}

		if(!$valid) {
			switch ($platform) {
				case 'browser':
					$title = 'Algunos componentes de esta página podrían no funcionar correctamente.';
					$msg1 = 'Para mejorar la experiencia en nuestro sitio asegúrate que estés usando:';

					if(!$browsersIn) {
						$msg1 = 'Aún no hemos validado la compatibilidad de nuestra aplicación con tu navegador.';
						$msg2 = 'Por el momento te sugerimos acceder con:';
					}
				break;
				case 'mobile':
					$msg1 = 'Nuestra aplicación no es compatible con tu dispositivo.';
					$msg2 = 'Por favor intenta desde una PC o MAC';
				break;
				case 'robot':
					$msg1 = 'No está permitodo el acceso de robots a nuestra aplicación.';
				break;
				default:
					$msg1 = 'No fue posible validar desde que plataforma intentas acceder.';
					$msg2 = 'Por favor intenta desde una PC o MAC';
				break;
			}

			$message = (object) [
				'platform' => $platform,
				'title' => isset($title) ? $title : '',
				'msg1' => $msg1,
				'msg2' => isset($msg2) ? $msg2 : ''
			];
			$this->CI->session->set_flashdata('messageBrowser', $message);
		}

		log_message('DEBUG', 'NOVO validBrowser: platform access '.$platform);

		return $valid;
	}
}
