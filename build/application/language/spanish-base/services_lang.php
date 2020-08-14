<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//CARDS INQUIRY
$lang['SERVICES_INQUIRY_OPTIONS'] = [
	'ACTUALIZAR_DATOS' => 'UPDATE_DATA',
	'CONSULTA_SALDO_TARJETA' => 'INQUIRY_BALANCE',
	'BLOQUEO_TARJETA' => 'LOCK_CARD',
	'DESBLOQUEO' => 'UNLOCK_CARD',
	'ENTREGAR_A_TARJETAHABIENTE' => 'DELIVER_TO_CARDHOLDER',
	'ENVIAR_A_EMPRESA' => 'SEND_TO_ENTERPRISE',
	'RECIBIR_EN_EMPRESA' => 'RECEIVE_IN_ENTERPRISE',
	'RECIBIR_EN_BANCO' => 'RECEIVE_IN_BANK'
];
$lang['SERVICES_INQUIRY_UPDATE_DATA'] = 'Actualizar datos';
$lang['SERVICES_INQUIRY_INQUIRY_BALANCE'] = 'Consultar saldo';
$lang['SERVICES_INQUIRY_LOCK_CARD'] = 'Bloquear tarjeta';
$lang['SERVICES_INQUIRY_UNLOCK_CARD'] = 'Desbloquear tarjeta';
$lang['SERVICES_INQUIRY_DELIVER_TO_CARDHOLDER'] = 'Entregar a tarjetahabiente';
$lang['SERVICES_INQUIRY_SEND_TO_ENTERPRISE'] = 'Enviar a empresa';
$lang['SERVICES_INQUIRY_RECEIVE_IN_ENTERPRISE'] = 'Recibir en empresa';
$lang['SERVICES_INQUIRY_RECEIVE_IN_BANK'] = 'Recibir en banco';
//INQUIRY ACTIONS
$lang['SERVICES_ACTION_UPDATE_DATA'] = 'act_datos';
$lang['SERVICES_ACTION_INQUIRY_BALANCE'] = 'saldo';
$lang['SERVICES_ACTION_LOCK_CARD'] = 'bloqueo';
$lang['SERVICES_ACTION_UNLOCK_CARD'] = 'desbloqueo';
$lang['SERVICES_ACTION_DELIVER_TO_CARDHOLDER'] = 'Entregada a Tarjetahabiente / Activa';
$lang['SERVICES_ACTION_SEND_TO_ENTERPRISE'] = 'Enviar a empresa';
$lang['SERVICES_ACTION_RECEIVE_IN_ENTERPRISE'] = 'Recibido en empresa';
$lang['SERVICES_ACTION_RECEIVE_IN_BANK'] = 'Recibido en Banco';
$lang['SERVICES_NAMES_PROPERTIES'] = [
	'agenciaViajes' => 'travelAgency',
	'aseguradoras' => 'insurers',
	'beneficencia' => 'charity',
	'colegios' => 'collegesUniversities',
	'entretenimiento' => 'entertainment',
	'estacionamientos' => 'parking',
	'gasolineras' => 'gaStations',
	'gobiernos' => 'governments',
	'hospitales' => 'hospitals',
	'hoteles' => 'hotels',
	'peajes' => 'debit',
	'rentaAuto' => 'toll',
	'restaurantes' => 'restaurants',
	'supermercados' => 'supermarkets',
	'telecomunicaciones' => 'telecommunication',
	'transporteAereo' => 'airTransport',
	'transporteTerrestre' => 'passengerTransportation',
	'ventasDetalle' => 'retailSales',
];
$lang['SERVICES_NAME_PROPERTIES_VIEW'] = [
	'agenciaViajes' => 'Agencia de viajes',
	'aseguradoras' => 'Aseguradoras',
	'beneficencia' => 'Beneficiencia',
	'colegios' => 'Colegios',
	'entretenimiento' => 'Entretenimiento',
	'estacionamientos' => 'Estacionamientos',
	'gasolineras' => 'Gasolineras',
	'gobiernos' => 'Gobiernos',
	'hospitales' => 'Hospitales',
	'hoteles' => 'Hoteles',
	'peajes' => 'Peajes',
	'rentaAuto' => 'Renta de auto',
	'restaurantes' => 'Restaurantes',
	'supermercados' => 'Supermercados',
	'telecomunicaciones' => 'Telecomunicaciones',
	'transporteAereo' => 'Trasnporte aereo',
	'transporteTerrestre' => 'Transporte terrestre',
	'ventasDetalle' => 'Ventas a detalle',
];

$lang['SERVICES_NAMES_PROPERTIES_LIMITS'] = [
	'abonoDiarioCant' => 'dailyNumberCredit',
	'abonoDiarioMonto' => 'dailyAmountCredit',
	'abonoMensualCant' => 'monthlyNumberCredit',
	'abonoMensualMonto' => 'monthlyAmountCredit',
	'abonoMontoTrx' => 'CreditTransaction',
	'abonoSemanalCant' => 'weeklyNumberCredit',
	'abonoSemanalMonto' => 'weeklyAmountCredit',
	'compraDiarioCant' => 'numberDayPurchasesCtp',
	'compraDiarioMonto' => 'dailyPurchaseamountCtp',
	'compraMensualCant' => 'numberMonthlyPurchasesCtp',
	'compraMensualMonto' => 'monthlyPurchasesAmountCtp',
	'compraMontoTrx' => 'purchaseTransactionCtp',
	'compraNoDiarioCant' => 'numberDayPurchasesStp',
	'compraNoDiarioMonto' => 'dailyPurchaseamountStp',
	'compraNoMensualCant' => 'numberMonthlyPurchasesStp',
	'compraNoMensualMonto' => 'monthlyPurchasesAmountStp',
	'compraNoMontoTrx' => 'purchaseTransactionStp',
	'compraNoSemanalCant' => 'numberWeeklyPurchasesStp',
	'compraNoSemanalMonto' => 'weeklyAmountPurchasesStp',
	'compraSemanalCant' => 'numberWeeklyPurchasesCtp',
	'compraSemanalMonto' => 'weeklyAmountPurchasesCtp',
	'retiroDiarioCant' => 'dailyNumberWithdraw',
	'retiroDiarioMonto' => 'dailyAmountWithdraw',
	'retiroMensualCant' => 'monthlyNumberWithdraw',
	'retiroMensualMonto' => 'monthlyAmountwithdraw',
	'retiroMontoTrx' => 'WithdrawTransaction',
	'retiroSemanalCant' => 'weeklyNumberWithdraw',
	'retiroSemanalMonto' => 'weeklyAmountWithdraw'
];

$lang['SERVICES_TWIRLS_TEMPORARY_BLOCKED_CARD'] = 'El número de tarjeta <strong>%s</strong> está bloqueada temporalmente.';
$lang['SERVICES_TWIRLS_PERMANENT_BLOCKED_CARD'] = 'El número de tarjeta <strong>%s</strong> está bloqueada permanentemente';
$lang['SERVICES_TWIRLS_NO_FOUND_REGISTRY'] = 'No ha sido encontrado el registro';
$lang['SERVICES_TWIRLS_EXPIRED_CARD'] = 'La tarjeta <strong>%s</strong> está vencida';
$lang['SERVICES_TWIRLS_NO_AVAILABLE_CARD'] = 'El número de tarjeta <strong>%s</strong> no está disponible para la restricción de giros en comercios.';
$lang['SERVICES_TWIRLS_NO_UPDATE'] = 'No fue posible actualizar la restricción de giros para:';
