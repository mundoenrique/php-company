<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['RESP_RC_DEFAULT'] = -9999;
$lang['RESP_DEFAULT_CODE'] = 4;
$lang['RESP_RC_0'] = 'Proceso ejecutado exitosamente';
$lang['RESP_DUPLICATED_SESSION'] = 'No fue posible validar tus credenciales de acceso, por favor vuelve a iniciar sesión, si continúas viendo este mensaje comunícate con el administrador.';
$lang['RESP_MESSAGE_SYSTEM'] = 'En este momento no podemos atender tu solicitud, por favor intenta más tarde';
$lang['RESP_TIMEOUT'] = "El servidor esta demorando mucho tiempo en responder por favor intentalo de nuevo";
$lang['RESP_VALIDATION_INPUT'] = 'Se detecto contenido no autorizado en la petición, serás desconectado.';
$lang['RESP_RECAPTCHA_VALIDATION_FAILED'] = 'El sistema ha detectado una actividad no autorizada, por favor intenta nuevamente';
$lang['RESP_INVALID_USER']= "Usuario o contraseña inválido";
$lang['RESP_SUSPENDED_USER'] = 'Usuario no disponible, se encuentra inactivo';
$lang['RESP_OLD_USER'] = 'Usuario aplicación anterior';
$lang['RESP_UNREGISTERED_USER'] = 'Usuario no registrado en el sistema.';
$lang['RESP_SUPPORT'] = '';
$lang['RESP_SUPPORT_MAIL'] = '';
$lang['RESP_SUPPORT_TELF'] = '';
$lang['RESP_INCORRECTLY_CLOSED'] = '<div><h5 class="regular">Tu última sesión se cerró de manera incorrecta. Ten en cuenta que para salir de la aplicación debes seleccionar <strong>"Cerrar Sesión"</strong>.</h5></div>';
$lang['RESP_NO_PERMISSIONS'] = 'Estimado usuario no tienes permiso para usar la aplicación, por favor comunícate con el administrador';
$lang['RESP_TEMP_PASS'] = '%s, enviamos un correo a %s, con una contraseña temporal.';
$lang['RESP_COMPANNY_NOT_ASSIGNED'] = 'El usuario %s no está asignado a la empresa.';
$lang['RESP_FISCAL_REGISTRY_NO_FOUND'] = 'Por favor vericia el Número de %s de la empresa.';
$lang['RESP_EMAIL_NO_FOUND'] = 'El correo %s es incorrecto, por favor verifícalo e intenta de nuevo.';
$lang['RESP_EMAIL_NO_SENT'] = 'No fue posible enviar el correo de recuperación, por favor intentalo de nuevo.';
$lang['RESP_PASSWORD_CHANGED'] = 'La contraseña fue cambiada exitosamente.<br>Por motivos de seguridad es necesario que inicies sesión nuevamente.';
$lang['RESP_PASSWORD_USED'] = 'La nueva contraseña no debe coincidir <strong>con las últimas cinco usadas</strong>.';
$lang['RESP_PASSWORD_INCORRECT'] = 'La contraseña actual es incorrecta.<br>Por favor verifícala e intenta de nuevo.';
$lang['RESP_UNCONFIGURED_PRODUCT'] = 'El producto no fue configurado correctamente, por favor comunícate con el administrador.';
$lang['RESP_NO_ACCESS'] = '%s, no tienes privilegios suficientes para manipular este producto.';
$lang['RESP_TRY_AGAIN'] = 'Intenta de nuevo';
$lang['RESP_PASSWORD_NO_VALID'] = 'Por vafor verifica tu contraseña y vuelve a intentarlo';
$lang['RESP_AUTH_ORDER_SERV'] = 'No fue posible obtener los datos para realizar el cálculo de la orden de servicio';
$lang['RESP_NO_LIST'] = 'No fue posible obtener el listado';
$lang['RESP_SERVICE_ORDES'] = 'No existen órdenes de servicio en estado "<strong>%s</strong>" para el rango de fecha seleccionado';
//UPLOAD FILE
$lang['RESP_UPLOAD_SFTP(0)'] = 'CURLE_PROCESS_OK';
$lang['RESP_UPLOAD_SFTP(1)'] = 'CURLE_UNSUPPORTED_PROTOCOL';
$lang['RESP_UPLOAD_SFTP(2)'] = 'CURLE_FAILED_INIT';
$lang['RESP_UPLOAD_SFTP(3)'] = 'CURLE_URL_MALFORMAT';
$lang['RESP_UPLOAD_SFTP(4)'] = 'CURLE_URL_MALFORMAT_USER';
$lang['RESP_UPLOAD_SFTP(5)'] = 'CURLE_COULDNT_RESOLVE_PROXY';
$lang['RESP_UPLOAD_SFTP(6)'] = 'CURLE_COULDNT_RESOLVE_HOST';
$lang['RESP_UPLOAD_SFTP(7)'] = 'CURLE_COULDNT_CONNECT';
$lang['RESP_UPLOAD_SFTP(8)'] = 'CURLE_FTP_WEIRD_SERVER_REPLY';
$lang['RESP_UPLOAD_SFTP(9)'] = 'CURLE_REMOTE_ACCESS_DENIED';
$lang['RESP_UPLOAD_SFTP(11)'] = 'CURLE_FTP_WEIRD_PASS_REPLY';
$lang['RESP_UPLOAD_SFTP(13)'] = 'CURLE_FTP_WEIRD_PASV_REPLY';
$lang['RESP_UPLOAD_SFTP(14)'] = 'CURLE_FTP_WEIRD_227_FORMAT';
$lang['RESP_UPLOAD_SFTP(15)'] = 'CURLE_FTP_CANT_GET_HOST';
$lang['RESP_UPLOAD_SFTP(17)'] = 'CURLE_FTP_COULDNT_SET_TYPE';
$lang['RESP_UPLOAD_SFTP(18)'] = 'CURLE_PARTIAL_FILE';
$lang['RESP_UPLOAD_SFTP(19)'] = 'CURLE_FTP_COULDNT_RETR_FILE';
$lang['RESP_UPLOAD_SFTP(21)'] = 'CURLE_QUOTE_ERROR';
$lang['RESP_UPLOAD_SFTP(22)'] = 'CURLE_HTTP_RETURNED_ERROR';
$lang['RESP_UPLOAD_SFTP(23)'] = 'CURLE_WRITE_ERROR';
$lang['RESP_UPLOAD_SFTP(25)'] = 'CURLE_UPLOAD_FAILED';
$lang['RESP_UPLOAD_SFTP(26)'] = 'CURLE_READ_ERROR';
$lang['RESP_UPLOAD_SFTP(27)'] = 'CURLE_OUT_OF_MEMORY';
$lang['RESP_UPLOAD_SFTP(28)'] = 'CURLE_OPERATION_TIMEDOUT';
$lang['RESP_UPLOAD_SFTP(30)'] = 'CURLE_FTP_PORT_FAILED';
$lang['RESP_UPLOAD_SFTP(31)'] = 'CURLE_FTP_COULDNT_USE_REST';
$lang['RESP_UPLOAD_SFTP(33)'] = 'CURLE_RANGE_ERROR';
$lang['RESP_UPLOAD_SFTP(34)'] = 'CURLE_HTTP_POST_ERROR';
$lang['RESP_UPLOAD_SFTP(35)'] = 'CURLE_SSL_CONNECT_ERROR';
$lang['RESP_UPLOAD_SFTP(36)'] = 'CURLE_BAD_DOWNLOAD_RESUME';
$lang['RESP_UPLOAD_SFTP(37)'] = 'CURLE_FILE_COULDNT_READ_FILE';
$lang['RESP_UPLOAD_SFTP(38)'] = 'CURLE_LDAP_CANNOT_BIND';
$lang['RESP_UPLOAD_SFTP(39)'] = 'CURLE_LDAP_SEARCH_FAILED';
$lang['RESP_UPLOAD_SFTP(41)'] = 'CURLE_FUNCTION_NOT_FOUND';
$lang['RESP_UPLOAD_SFTP(42)'] = 'CURLE_ABORTED_BY_CALLBACK';
$lang['RESP_UPLOAD_SFTP(43)'] = 'CURLE_BAD_FUNCTION_ARGUMENT';
$lang['RESP_UPLOAD_SFTP(45)'] = 'CURLE_INTERFACE_FAILED';
$lang['RESP_UPLOAD_SFTP(47)'] = 'CURLE_TOO_MANY_REDIRECTS';
$lang['RESP_UPLOAD_SFTP(48)'] = 'CURLE_UNKNOWN_TELNET_OPTION';
$lang['RESP_UPLOAD_SFTP(49)'] = 'CURLE_TELNET_OPTION_SYNTAX';
$lang['RESP_UPLOAD_SFTP(51)'] = 'CURLE_PEER_FAILED_VERIFICATION';
$lang['RESP_UPLOAD_SFTP(52)'] = 'CURLE_GOT_NOTHING';
$lang['RESP_UPLOAD_SFTP(53)'] = 'CURLE_SSL_ENGINE_NOTFOUND';
$lang['RESP_UPLOAD_SFTP(54)'] = 'CURLE_SSL_ENGINE_SETFAILED';
$lang['RESP_UPLOAD_SFTP(55)'] = 'CURLE_SEND_ERROR';
$lang['RESP_UPLOAD_SFTP(56)'] = 'CURLE_RECV_ERROR';
$lang['RESP_UPLOAD_SFTP(58)'] = 'CURLE_SSL_CERTPROBLEM';
$lang['RESP_UPLOAD_SFTP(59)'] = 'CURLE_SSL_CIPHER';
$lang['RESP_UPLOAD_SFTP(60)'] = 'CURLE_SSL_CACERT';
$lang['RESP_UPLOAD_SFTP(61)'] = 'CURLE_BAD_CONTENT_ENCODING';
$lang['RESP_UPLOAD_SFTP(62)'] = 'CURLE_LDAP_INVALID_URL';
$lang['RESP_UPLOAD_SFTP(63)'] = 'CURLE_FILESIZE_EXCEEDED';
$lang['RESP_UPLOAD_SFTP(64)'] = 'CURLE_USE_SSL_FAILED';
$lang['RESP_UPLOAD_SFTP(65)'] = 'CURLE_SEND_FAIL_REWIND';
$lang['RESP_UPLOAD_SFTP(66)'] = 'CURLE_SSL_ENGINE_INITFAILED';
$lang['RESP_UPLOAD_SFTP(67)'] = 'CURLE_LOGIN_DENIED';
$lang['RESP_UPLOAD_SFTP(68)'] = 'CURLE_TFTP_NOTFOUND';
$lang['RESP_UPLOAD_SFTP(69)'] = 'CURLE_TFTP_PERM';
$lang['RESP_UPLOAD_SFTP(70)'] = 'CURLE_REMOTE_DISK_FULL';
$lang['RESP_UPLOAD_SFTP(71)'] = 'CURLE_TFTP_ILLEGAL';
$lang['RESP_UPLOAD_SFTP(72)'] = 'CURLE_TFTP_UNKNOWNID';
$lang['RESP_UPLOAD_SFTP(73)'] = 'CURLE_REMOTE_FILE_EXISTS';
$lang['RESP_UPLOAD_SFTP(74)'] = 'CURLE_TFTP_NOSUCHUSER';
$lang['RESP_UPLOAD_SFTP(75)'] = 'CURLE_CONV_FAILED';
$lang['RESP_UPLOAD_SFTP(76)'] = 'CURLE_CONV_REQD';
$lang['RESP_UPLOAD_SFTP(77)'] = 'CURLE_SSL_CACERT_BADFILE';
$lang['RESP_UPLOAD_SFTP(78)'] = 'CURLE_REMOTE_FILE_NOT_FOUND';
$lang['RESP_UPLOAD_SFTP(79)'] = 'CURLE_SSH';
$lang['RESP_UPLOAD_SFTP(80)'] = 'CURLE_SSL_SHUTDOWN_FAILED';
$lang['RESP_UPLOAD_SFTP(81)'] = 'CURLE_AGAIN';
$lang['RESP_UPLOAD_SFTP(82)'] = 'CURLE_SSL_CRL_BADFILE';
$lang['RESP_UPLOAD_SFTP(83)'] = 'CURLE_SSL_ISSUER_ERROR';
$lang['RESP_UPLOAD_SFTP(84)'] = 'CURLE_FTP_PRET_FAILED';
$lang['RESP_UPLOAD_SFTP(84)'] = 'CURLE_FTP_PRET_FAILED';
$lang['RESP_UPLOAD_SFTP(85)'] = 'CURLE_RTSP_CSEQ_ERROR';
$lang['RESP_UPLOAD_SFTP(86)'] = 'CURLE_RTSP_SESSION_ERROR';
$lang['RESP_UPLOAD_SFTP(87)'] = 'CURLE_FTP_BAD_FILE_LIST';
$lang['RESP_UPLOAD_SFTP(88)'] = 'CURLE_CHUNK_FAILED';
