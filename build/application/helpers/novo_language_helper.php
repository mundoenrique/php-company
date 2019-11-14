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
if ( ! function_exists('novoLang'))
{
	function novoLang($line, $args = [])
	{
		//$line = get_instance()->lang->line($line);

		$line = vsprintf($line, (array) $args);

		return $line;
	}
}
