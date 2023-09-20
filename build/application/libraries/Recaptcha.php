<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * CodeIgniter Recaptcha library
 *
 * @package CodeIgniter
 * @author  Bo-Yi Wu <appleboy.tw@gmail.com>
 * @link    https://github.com/appleboy/CodeIgniter-reCAPTCHA
 */
class Recaptcha
{
	/**
	 * CI instance object
	 *
	 */
	private $CI;
	/**
	 * reCAPTCHA site up, verify and api url.
	 *
	 */
	const sign_up_url = 'https://www.google.com/recaptcha/admin';
	const site_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
	const api_url = 'https://www.google.com/recaptcha/api.js';
	/**
	 * constructor
	 *
	 * @param string $config
	 */
	public function __construct()
	{
		writeLog('INFO', 'Recaptcha Library Class Initialized');

		$this->CI = &get_instance();
		$this->CI->load->config('recaptcha');
		$this->siteKey = $this->CI->config->item('recaptcha_site_key');
		$this->secretKey = $this->CI->config->item('recaptcha_secret_key');
		$this->language = $this->CI->config->item('recaptcha_lang');
		if (empty($this->siteKey) or empty($this->secretKey)) {
			die("To use reCAPTCHA you must get an API key from <a href='"
				. self::sign_up_url . "'>" . self::sign_up_url . "</a>");
		}
	}

	/**
	 * Calls the reCAPTCHA siteverify API to verify whether the user passes
	 * CAPTCHA test.
	 *
	 * @param string $response response string from recaptcha verification.
	 * @param string $remoteIp IP address of end user.
	 *
	 * @return ReCaptchaResponse
	 */
	public function verifyResponse($captcha)
	{
		$ip = $this->CI->input->ip_address();

		$data = [
			'secret' => $this->secretKey,
			'response' => $captcha,
			'remoteip' => $ip
		];

		writeLog('DEBUG', 'RECAPTCHA REQUEST: ' . json_encode($data, JSON_UNESCAPED_UNICODE));

		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$response = file_get_contents(self::site_verify_url, false, $context);
		$responseKeys = json_decode($response);
		// header('Content-type: application/json');

		return $responseKeys;
	}
	/**
	 * Render Script Tag
	 *
	 * onload: Optional.
	 * render: [explicit|onload] Optional.
	 * hl: Optional.
	 * see: https://developers.google.com/recaptcha/docs/display
	 *
	 * @param array parameters.
	 *
	 * @return scripts
	 */
	public function getScriptTag(array $parameters = array())
	{
		$default = array(
			'render' => $this->siteKey
		);
		$result = array_merge($default, $parameters);
		$scripts = sprintf(
			'<script type="text/javascript" async defer src="%s?%s"></script>',
			self::api_url,
			http_build_query($result)
		);

		return $scripts;
	}
}
