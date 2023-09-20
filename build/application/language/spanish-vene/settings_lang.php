<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['SETT_VIEW_SUFFIX'] = '';
$lang['SETT_VALID_ERROR'] = 'validate-error';
$lang['SETT_VALID_VALID'] = 'success';
$lang['SETT_VALID_SUCCESS'] = ' ';
$lang['SETT_VALID_IGNORE'] = '.ignore';
$lang['SETT_VALID_ELEMENT'] = 'label';
$lang['SETT_VALID_INVALID_USER'] = 'error-login-2';
$lang['SETT_VALID_INACTIVE_USER'] = 'login-inactive';
$lang['SETT_VALID_POSITION'] = 'left';
$lang['SETT_MODAL_WIDTH'] = 310;
$lang['SETT_LINK_SIGNIN'] = 'inicio';
$lang['SETT_LINK_CHANGE_PASS'] = 'cambiar-clave';
$lang['SETT_LINK_ENTERPRISES'] = 'dashboard';
$lang['SETT_LINK_TERMS'] = 'inf-condiciones';
$lang['SETT_LINK_SUGGESTION'] = 'browsers';
$lang['SETT_SIGNIN_IMG'] = 'OFF';
$lang['SETT_MAINT_NOTIF'] = strtotime(date("d-m-Y H:i:00", time())) < strtotime("23-07-2023 14:00:00") ? 'ON' : 'OFF';
$lang['SETT_MODAL_BTN_CLASS'] = [
	'cancel' => 'btn-modal cancel-button novo-btn-secondary-modal dialog-buttons',
	'accept' => 'btn-modal novo-btn-primary-modal dialog-buttons'
];
