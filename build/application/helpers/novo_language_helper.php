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
if (!function_exists('novoLang'))
{
	function novoLang($line, $args = [])
	{
		$line = vsprintf($line, (array) $args);

		return $line;
	}
}

if (!function_exists('BulkAttrEmissionA'))
{
	function BulkAttrEmissionA()
	{
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

if (!function_exists('BulkAttrEmissionB'))
{
	function BulkAttrEmissionB()
	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
		$tableContent->body = ['idExtPer', 'nombres', 'apellidos', 'nroTarjeta'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrEmissionC'))
{
	function BulkAttrEmissionC()
	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_EMAIL'), lang('GEN_TABLE_STATUS')];
		$tableContent->body = ['idExtPer', 'nombres', 'apellidos', 'email', 'status'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrCreditsA'))
{
	function BulkAttrCreditsA()
	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
		$tableContent->body = ['id_ext_per', 'monto', 'nro_cuenta'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrCreditsB'))
{
	function BulkAttrCreditsB()
	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_STATUS')];
		$tableContent->body = ['id_ext_per', 'monto', 'nro_cuenta', 'status'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrCreditsC'))
{
	function BulkAttrCreditsC()
	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_STATUS')];
		$tableContent->body = ['id_ext_per', 'monto', 'nro_cuenta', 'status'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrKindergastenA'))
{
	function BulkAttrKindergastenA()
	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_EMPLOYEE'), lang('GEN_TABLE_BENEFICIARY')];
		$tableContent->body = ['idExtPer', 'nombre', 'apellido', 'beneficiario'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrKindergastenB'))
{
	function BulkAttrKindergastenB()
	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_EMPLOYEE'), lang('GEN_TABLE_BENEFICIARY'), lang('GEN_TABLE_ACCOUNT_BENEFICIARY')];
		$tableContent->body = ['idExtPer', 'nombre', 'apellido', 'beneficiario', 'nro_cuenta'];

		return $tableContent;
	}
}

if (!function_exists('BulkAttrReplacementA'))
{
	function BulkAttrReplacementA()
	{
		$CI = &get_instance();
		$tableContent = new stdClass();
		$tableContent->header = [lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_DNI')];
		$tableContent->body = ['aced_rif', 'nocuenta'];

		return $tableContent;
	}
}
