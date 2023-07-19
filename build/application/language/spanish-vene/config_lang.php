<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$lang['CONF_VIEW_SUFFIX'] = '';
$lang['CONF_VALID_ERROR'] = 'validate-error';
$lang['CONF_VALID_VALID'] = 'success';
$lang['CONF_VALID_SUCCESS'] = ' ';
$lang['CONF_VALID_IGNORE'] = '.ignore';
$lang['CONF_VALID_ELEMENT'] = 'label';
$lang['CONF_VALID_INVALID_USER'] = 'error-login-2';
$lang['CONF_VALID_INACTIVE_USER'] = 'login-inactive';
$lang['CONF_VALID_POSITION'] = 'left';
$lang['CONF_MODAL_WIDTH'] = 310;
$lang['CONF_LINK_SIGNIN'] = 'inicio';
$lang['CONF_LINK_CHANGE_PASS'] = 'cambiar-clave';
$lang['CONF_LINK_ENTERPRISES'] = 'dashboard';
$lang['CONF_LINK_TERMS'] = 'inf-condiciones';
$lang['CONF_LINK_SUGGESTION'] = 'browsers';
$lang['CONF_SIGNIN_IMG'] = 'OFF';
$lang['CONF_MAINT_NOTIF'] = strtotime(date("d-m-Y H:i:00", time())) < strtotime("23-07-2023 14:00:00") ? 'ON' : 'OFF';
