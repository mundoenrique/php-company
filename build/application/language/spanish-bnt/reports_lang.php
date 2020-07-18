<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['REPORTS_ID_FISCAL']= "RFC. (Opcional)";
$lang['REPORTS_ID_FISCAL_INPUT']= "Ingresa RFC.";
//MASTER ACCOUNT
$lang['REPORTS_TABLE_DATE']="Fecha";
$lang['REPORTS_TABLE_DESCRIPTION']="Descripción";
$lang['REPORTS_TABLE_REFERENCE']="Referencia";
$lang['REPORTS_TABLE_DEBIT']="Cargo";
$lang['REPORTS_TABLE_CREDIT']="Abono";
$lang['REPORTS_TABLE_BALANCE']="Saldo";
//CLOSING BALANCE
$lang['REPORTS_TABLE_CARD']="Tarjeta";
$lang['REPORTS_TABLE_CARDHOLDER']="Tarjetahabiente";
$lang['REPORTS_TABLE_DNI']="CURP";
$lang['REPORTS_COLUMNS']= [
	[ '[{ "data": "tarjeta"}, { "data": "nombre"},
	{ "data": "idExtPer"}, { "data": "saldo"}]' ]

];
$lang['REPORTS_COLUMNS_REFS']= [
	[ '[{"targets": 0,"className": "tarjeta"},{"targets": 1,"className": "nombre"},{"targets": 2,"className": "idExtPer"},{"targets": 3,"className": "saldo"}]' ]
];

