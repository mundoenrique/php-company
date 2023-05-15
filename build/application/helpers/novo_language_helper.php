<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * NOVOPAYMENT Language Helpers
 *
 * @category	Helpers
 * @author		Enrique Peñaloza
 * @date			24/10/2019
 * @ingo			Helper para interpolar variables en las varibales de lenguaje
 */
if (!function_exists('novoLang')) {
	function novoLang($line, $args = []) {
		$line = vsprintf($line, (array) $args);

		return $line;
	}
}

if (!function_exists('LoadLangFile')) {
	function LoadLangFile($call, $fileLanguage, $customerLang) {
		writeLog('INFO', 'Helper language loaded: LoadLangFile_helper for ' . $call . ' files');

		$CI =& get_instance();
		$languagesFile = [];
		$loadLanguages = FALSE;
		$configLanguage = $CI->config->item('language');
		$customerLang = tenantSameSettings($customerLang);
		$pathLang = APPPATH . 'language' . DIRECTORY_SEPARATOR . $configLanguage . DIRECTORY_SEPARATOR;

		switch ($call) {
			case 'generic':
				if(file_exists($pathLang . 'settings_' . $customerLang . '_lang.php')) {
					$CI->lang->load('settings_' . $customerLang);
				}

				if(file_exists($pathLang . 'images_' . $customerLang . '_lang.php')) {
					$CI->lang->load('images_' . $customerLang);
				}

				if(file_exists($pathLang . 'regexp_' . $customerLang . '_lang.php')) {
					$CI->lang->load('regexp_' . $customerLang);
				}

				$CI->config->set_item('language', BASE_LANGUAGE . '-base');
				array_push($languagesFile, 'general', 'validate');
				$loadLanguages = TRUE;
				$pathLang = APPPATH . 'language' . DIRECTORY_SEPARATOR . BASE_LANGUAGE . '-base' . DIRECTORY_SEPARATOR;
				break;

				case 'specific':
					if (file_exists($pathLang . 'general_lang.php')) {
						array_push($languagesFile, 'general');
						$loadLanguages = TRUE;
					}

					if (file_exists($pathLang . 'validate_lang.php')) {
						array_push($languagesFile, 'validate');
						$loadLanguages = TRUE;
					}

					//Borrar al finalizar la migración
					if (file_exists($pathLang . 'settings_lang.php')) {
						array_push($languagesFile, 'settings');
						$loadLanguages = TRUE;
					}
					break;
		}

		if (file_exists($pathLang . $fileLanguage . '_lang.php')) {
			array_push($languagesFile, $fileLanguage);
			$loadLanguages = TRUE;
		}

		if ($loadLanguages) {
			$CI->lang->load($languagesFile);
		}
	}
}

if (!function_exists('languageCookie')) {
	function languageCookie($language) {
		$baseLanguage = [
			'name' => 'baseLanguage',
			'value' => $language,
			'expire' => 0,
			'httponly' => TRUE
		];

		set_cookie($baseLanguage);
	}
}

if (!function_exists('BulkAttrEmissionA')) {
	function BulkAttrEmissionA() {
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_STATUS')];
		$tableContent->body = ['idExtPer', 'nombres', 'apellidos', 'status'];

		switch ($CI->config->item('customer_uri')) {
			case 'pb':
			case 'bp':
				$tableContent->header = [
					lang('GEN_TABLE_ID_TYPE'), lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_BRANCH_COD'), lang('GEN_TABLE_STATUS')
				];
				$tableContent->body = ['typeIdentification', 'idExtPer', 'nombres', 'apellidos', 'ubicacion', 'status'];
			break;
		}

		return $tableContent;
	}
}

if (!function_exists('BulkAttrEmissionB')) {
	function BulkAttrEmissionB() {
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
		$tableContent->body = ['idExtPer', 'nombres', 'apellidos', 'nroTarjeta'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrEmissionC')) {
	function BulkAttrEmissionC() {
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_EMAIL'), lang('GEN_TABLE_STATUS')];
		$tableContent->body = ['idExtPer', 'nombres', 'apellidos', 'email', 'status'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrCreditsA')) {
	function BulkAttrCreditsA()	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
		$tableContent->body = ['id_ext_per', 'monto', 'nro_cuenta'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrCreditsB')) {
	function BulkAttrCreditsB() {
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_STATUS')];
		$tableContent->body = ['id_ext_per', 'monto', 'nro_cuenta', 'status'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrCreditsC')) {
	function BulkAttrCreditsC() {
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_STATUS')];
		$tableContent->body = ['id_ext_per', 'monto', 'nro_cuenta', 'status'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrKindergastenA')) {
	function BulkAttrKindergastenA() {
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_EMPLOYEE'), lang('GEN_TABLE_BENEFICIARY')];
		$tableContent->body = ['idExtPer', 'nombre', 'apellido', 'beneficiario'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrKindergastenB')) {
	function BulkAttrKindergastenB() {
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_EMPLOYEE'), lang('GEN_TABLE_BENEFICIARY'), lang('GEN_TABLE_ACCOUNT_BENEFICIARY')];
		$tableContent->body = ['idExtPer', 'nombre', 'apellido', 'beneficiario', 'nro_cuenta'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrReplacementA')) {
	function BulkAttrReplacementA()	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_DNI')];
		$tableContent->body = ['aced_rif', 'nocuenta'];

		return $tableContent;
	}
}
