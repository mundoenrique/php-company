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
		$this->CI->load->library('user_agent');
		$valid = FALSE;
		$platform = 'Unidentified';
		$browser = FALSE;

		if($this->CI->agent->is_browser()) {
			$platform = 'browser';
			$validBrowser = [
				'Chrome' => 47,
				'Firefox' => 29,
				'Opera' => 36,
				'Safari' => 9,
				'Edge' => 13,
				'Internet Explorer' => 11
			];

			if(array_key_exists($this->CI->agent->browser(), $validBrowser)) {
				if(in_array($client, ['novo', 'pichincha', 'banco-bog'])) {
					$validBrowser['Internet Explorer'] = 10;
				}

				$browser = TRUE;
				$valid = floatval($this->CI->agent->version()) > $validBrowser[$this->CI->agent->browser()];
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


					if(!$browser) {
						$title = '';
						$msg1 = 'Aún no hemos validado la compatibilidad de nuestra aplicación con tu navegador.';
						$msg2 = 'Por el momento te sugerimos acceder con';
					}
					break;
				case 'mobile':
					$title = '';
					$msg1 = 'Nuestra aplicación no es compatible con tu dispositivo.';
					$msg2 = '';
					break;
				case 'robot':
					$title = '';
					$msg1 = 'No está permitodo el acceso de robots a nuestra aplicación.';
					$msg2 = '';
					break;
				default:
					$title = '';
					$msg1 = 'No fue posible validar desde que plataforma intentas acceder.';
					$msg2 = 'Por favor intenta desde una PC o MAC';
					break;
			}

			$message = (object) [
				'platform' => $platform,
				'title' => $title,
				'msg1' => $msg1,
				'msg2' => $msg2
			];

			$this->CI->session->set_flashdata('messageBrowser', $message);
		}


		return $valid;
	}
}
