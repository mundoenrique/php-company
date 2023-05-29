<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$lang['SERVICES_BLOCKING_CARD'] = 'La tarjeta %s ha sido %s.';
$lang['SERVICES_ASSIGNMENT_CARD'] = 'La tarjeta %s ha sido reemplazada por %s.';
$lang['SERVICES_NOT_LOCKED'] = 'No fue posible realizar el bloqueo %s';
$lang['SERVICES_NONLOCKED_ACTION'] = 'No fue posible realizar el bloqueo, intenta de nuevo';
$lang['SERVICES_BALANCE_NO_FOUND'] = 'No fue posible obtener el saldo para';
$lang['SERVICES_TRANSACTION_DATA'] = 'Datos de la transacción';
$lang['SERVICES_TRANSACTION_FAIL'] = 'No fue posible realizar la trasacción, por favor intentalo de nuevo.';
$lang['SERVICES_BALANCE_NO_AVAILABLE'] = 'El saldo no esta disponible.';
$lang['SERVICES_BALANCE_NO_SEARCH'] = 'No fue posible realizar la consulta de saldo, intenta de nuevo';
$lang['SERVICES_MIN_AMOUNT'] = 'La transacción no supera el monto mínimo por operación.';
$lang['SERVICES_MAX_WEEKLY_AMOUNT'] = 'Alcanzaste el monto máximo de operaciones semenales.';
$lang['SERVICES_MAX_DAILY_AMOUNT'] = 'Alcanzaste el monto máximo de operaciones diarias.';
$lang['SERVICES_MAX_OPERATION'] = 'Excediste el límite de transacciones. Consulta los límites transaccionales configurados para la tarjeta.';
$lang['SERVICES_MAX_WEEKLY_OPERATION'] = 'Alcanzaste el límite de operaciones semanales.';
$lang['SERVICES_MAX_DAILY_OPERATION'] = 'Alcanzaste el límite de operaciones diarias.';
$lang['SERVICES_NO_BALANCE'] = 'Tu saldo no es suficiente para realizar la transacción.';
$lang['SERVICES_UNAVAILABLE_BALANCE'] = 'El saldo no está disponible.';
$lang['SERVICES_INACTIVE_ACCOUNT'] = 'La cuenta se encuentra inactiva.';
$lang['SERVICES_SUCCESSFUL_TRANSFER'] = 'La transferencia fue realizada exitosamente.';
$lang['SERVICES_BLOCKED_CARD'] = 'La tarjeta %s ya se encunetra bloqueda.';
$lang['SERVICES_PENDING_MEMBER_SHIP'] = 'El empleado tiene una afiliación pendiente';
$lang['SERVICES_USER_BULK_CONFIRM'] = 'El empleado esta en un lote por confirmar';
$lang['SERVICES_CARD_BULK_CONFIRM'] = 'La tarjeta %s esta en un lote por comfirmar.';
$lang['SERVICES_CARD_BULK_AFFILIATED'] = 'La tarjeta %s ya fue afiliada o esta por afiliar en un lote confirmado.';
$lang['SERVICES_CARD_TRANSFER_BALANCE'] = 'No fue posible transferir el saldo a la tarjeta destino, por favor intenta más tarde.';
$lang['SERVICES_RESPONSE_CARD_CANCELED'] = '¿Está seguro de que desea cancelar la tarjeta? Esta operación no puede ser reversada.';
$lang['SERVICES_REASON_REQUEST'] = 'Tipo de solicitud';
$lang['SERVICES_REASON_LOCK_TYPES'] = 'Indica el tipo de bloqueo';
$lang['SERVICES_LOCK_TYPES_BLOCK'] = [
	'PB' => 'Bloqueo temporal',
	'41' => 'Bloqueo por extravío',
	'43' => 'Bloqueo por robo'
];
$lang['SERVICES_INQUIRY_OPTIONS'] = [
	'ACTUALIZAR_DATOS' => 'UPDATE_DATA',
	'CONSULTA_SALDO_TARJETA' => 'INQUIRY_BALANCE',
	'BLOQUEO_TARJETA' => 'LOCK_CARD',
	'DESBLOQUEO' => 'UNLOCK_CARD',
	'ENTREGAR_A_TARJETAHABIENTE' => 'DELIVER_TO_CARDHOLDER',
	'ENVIAR_A_EMPRESA' => 'SEND_TO_ENTERPRISE',
	'RECIBIR_EN_EMPRESA' => 'RECEIVE_IN_ENTERPRISE',
	'RECIBIR_EN_BANCO' => 'RECEIVE_IN_BANK',
	'CANCELAR_TARJETA' => 'CARD_CANCELLATION'
];
$lang['SERVICES_INQUIRY_OPTIONS_ICON'] = [
	'UPDATE_DATA' => 'user-edit',
	'INQUIRY_BALANCE' => 'envelope-open',
	'LOCK_CARD' => 'lock',
	'UNLOCK_CARD' => 'unlock',
	'DELIVER_TO_CARDHOLDER' => 'deliver-card',
	'SEND_TO_ENTERPRISE' => 'shipping',
	'RECEIVE_IN_ENTERPRISE' => 'building',
	'RECEIVE_IN_BANK' => 'user-building',
	'CARD_CANCELLATION' => 'card-canceled'
];
$lang['SERVICES_INQUIRY_UPDATE_DATA'] = 'Actualizar datos';
$lang['SERVICES_INQUIRY_INQUIRY_BALANCE'] = 'Consultar saldo';
$lang['SERVICES_INQUIRY_LOCK_CARD'] = 'Bloquear tarjeta';
$lang['SERVICES_INQUIRY_UNLOCK_CARD'] = 'Desbloquear tarjeta';
$lang['SERVICES_INQUIRY_DELIVER_TO_CARDHOLDER'] = 'Entregar a tarjetahabiente';
$lang['SERVICES_INQUIRY_SEND_TO_ENTERPRISE'] = 'Enviar a empresa';
$lang['SERVICES_INQUIRY_RECEIVE_IN_ENTERPRISE'] = 'Recibir en empresa';
$lang['SERVICES_INQUIRY_RECEIVE_IN_BANK'] = 'Recibir en banco';
$lang['SERVICES_INQUIRY_CARD_CANCELLATION'] = 'Cancelación de tarjeta';
//INQUIRY ACTIONS
$lang['SERVICES_ACTION_UPDATE_DATA'] = 'act_datos';
$lang['SERVICES_ACTION_INQUIRY_BALANCE'] = 'saldo';
$lang['SERVICES_ACTION_LOCK_CARD'] = 'bloqueo';
$lang['SERVICES_ACTION_UNLOCK_CARD'] = 'desbloqueo';
$lang['SERVICES_ACTION_DELIVER_TO_CARDHOLDER'] = 'Entregada a Tarjetahabiente / Activa';
$lang['SERVICES_ACTION_SEND_TO_ENTERPRISE'] = 'Enviar a empresa';
$lang['SERVICES_ACTION_RECEIVE_IN_ENTERPRISE'] = 'Recibido en empresa';
$lang['SERVICES_ACTION_RECEIVE_IN_BANK'] = 'Recibido en Banco';
$lang['SERVICES_ACTION_CARD_CANCELLATION'] = 'Cancelar tarjeta';
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
$lang['SERVICES_NO_FOUND_REGISTRY'] = 'El número de tarjeta <strong>%s</strong> no se ha encontrado.';
$lang['SERVICES_TWIRLS_EXPIRED_CARD'] = 'La tarjeta <strong>%s</strong> está vencida';
$lang['SERVICES_TWIRLS_NO_AVAILABLE_CARD'] = 'El número de tarjeta <strong>%s</strong> no está disponible.';
$lang['SERVICES_TWIRLS_NO_UPDATE'] = 'No fue posible actualizar la restricción de giros para:';
$lang['SERVICES_LIMITS_NO_REGISTRY'] = 'No se han encontrado límites disponibles.';
$lang['SERVICES_LIMITS_NO_UPDATE'] = 'No fue posible actualizar la restricción de límites';
$lang['SERVICES_REFERENCE'] = 'Referencia';
$lang['SERVICES_AVAILABLE_BALANCE'] = 'Saldo cuenta maestra:';
$lang['SERVICES_COMMISSION_TRANS'] = 'Comisión por transacción:';
$lang['SERVICES_COMMISSION_CONSULTATION'] = 'Comisión por consultar saldo:';
$lang['SERVICES_BALANCE_ACC_CONCENTRATOR'] = 'Saldo cuenta concentradora:';
$lang['SERVICES_BALANCE_ACC_ADMINISTRATOR'] = 'Saldo cuenta administradora:';
$lang['SERVICES_TABLE_NO_REGISTRY_MASTERACCOUNT'] = 'El listado de tarjetas no se encuentra disponible. Por favor verifica si tienes tarjetas afiliadas, ó realiza la búsqueda nuevamente.';
$lang["SERVICES_SUCCESFUL_TRANSACTION"] = 'Transacción Exitosa.';
$lang["SERVICES_LIMIT_EXCEEDED"] = 'Límite Excedido.';
$lang["SERVICES_INSUFFICIENT_BALANCE"] = 'Saldo Insuficiente.';
$lang["SERVICES_LOCKED_CARD"] = 'Tarjeta bloqueada.';
$lang["SERVICES_FAILED_TRANSACTION"] = 'No fue posible realizar la transacción.';
$lang["SERVICES_TITLE_RECHARGE_MASTER_ACCOUNT"] = 'Recarga cuenta/tarjeta maestra';
$lang["SERVICES_TYPE_CARGO"] = 'Cargo';
$lang["SERVICES_TYPE_ABONO"] = 'Abono';
