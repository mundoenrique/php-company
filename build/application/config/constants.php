<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION VARIABLES
|--------------------------------------------------------------------------
*/
$db_port = (isset($_SERVER['DB_PORT'])) ? intval($_SERVER['DB_PORT']) : NULL;
defined('DB_HOSTNAME')	OR define('DB_HOSTNAME', $_SERVER['DB_HOSTNAME']);
defined('DB_PORT')			OR define('DB_PORT', $db_port);
defined('DB_USERNAME')	OR define('DB_USERNAME', $_SERVER['DB_USERNAME']);
defined('DB_PASSWORD')	OR define('DB_PASSWORD', $_SERVER['DB_PASSWORD']);
defined('DB_DRIVER')		OR define('DB_DRIVER', $_SERVER['DB_DRIVER'] ?? 'mysqli');
defined('DB_CHARSET')		OR define('DB_CHARSET', $_SERVER['DB_CHARSET'] ?? 'utf8');
defined('DB_COLLATION')	OR define('DB_COLLATION', $_SERVER['DB_COLLATION'] ?? 'utf8_general_ci');

/*
|--------------------------------------------------------------------------
| Environment variables
|--------------------------------------------------------------------------
|
| Constants expected as environment variables on the instance to be used
| as part of global configuration settings.
|
*/
$uriSegments  =  explode( "/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$proxyIps = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) ? 'public' : 'private';
$timeZone = [
	'bdb'	=>	'America/Bogota',
	'bg' 	=>	'America/Guayaquil',
	'bnt'	=>	'America/Mexico_City',
	'bp'	=>	'America/Guayaquil',
	'col'	=>	'America/Bogota',
	'pb'	=>	'America/Guayaquil',
	'per'	=>	'America/Lima',
	'usd'	=>	'America/Lima',
	'ven'	=>	'America/Caracas',
	'vg'	=>	'America/Lima',
];
$errorController = array_key_exists($uriSegments[1], $timeZone) ? 'Novo_Errors/pageNoFound' : '';
$timeZone = array_key_exists($uriSegments[1], $timeZone) ? $timeZone[$uriSegments[1]] : 'America/New_York';
date_default_timezone_set($timeZone);
$baseLanguage = 'spanish';

switch(end($uriSegments)) {
	case 'es':
		$baseLanguage = 'spanish';
	break;
	case 'en':
		$baseLanguage = 'english';
	break;
	default:
		if (isset($_COOKIE['ceo_baseLanguage']) && ($_COOKIE['ceo_baseLanguage'] === 'spanish' || $_COOKIE['ceo_baseLanguage'] === 'english')) {
			$baseLanguage = $_COOKIE['ceo_baseLanguage'];
		}
}
/*
|--------------------------------------------------------------------------
| FRAMEWORK SETTINGS
|--------------------------------------------------------------------------
*/
defined('BASE_URL')					OR define('BASE_URL', $_SERVER['BASE_URL']);
defined('ASSET_URL')				OR define('ASSET_URL', $_SERVER['ASSET_URL']);
defined('ASSET_PATH')				OR define('ASSET_PATH', $_SERVER['ASSET_PATH']);
defined('BASE_LANGUAGE')		OR define('BASE_LANGUAGE', $baseLanguage);
defined('THRESHOLD')				OR define('THRESHOLD', $_SERVER['CI_ENV'] === 'development' ? 4 : 2);
defined('LOG_PATH')					OR define('LOG_PATH', $_SERVER['LOG_PATH'] ?? '');
defined('ENCRYPTION_KEY')		OR define('ENCRYPTION_KEY', $_SERVER['ENCRYPTION_KEY'] ?? '3NCRYPT10N');
defined('SESS_DRIVER')			OR define('SESS_DRIVER', $_SERVER['SESS_DRIVER'] ?? 'files');
defined('SESS_COOKIE_NAME')	OR define('SESS_COOKIE_NAME', $_SERVER['SESS_COOKIE_NAME'] ?? 'session');
defined('SESS_EXPIRATION')	OR define('SESS_EXPIRATION', intval($_SERVER['SESS_EXPIRATION']));
defined('SESS_SAVE_PATH')		OR define('SESS_SAVE_PATH', $_SERVER['SESS_SAVE_PATH'] ?? NULL);
defined('COOKIE_PREFIX')		OR define('COOKIE_PREFIX', $_SERVER['COOKIE_PREFIX']);
defined('COOKIE_DOMAIN')		OR define('COOKIE_DOMAIN', $_SERVER['COOKIE_DOMAIN']);
defined('COOKIE_SECURE')		OR define('COOKIE_SECURE', $_SERVER['COOKIE_SECURE']);
defined('PROXY_IPS')				OR define('PROXY_IPS', $proxyIps == 'private' ? $_SERVER['REMOTE_ADDR'] : '');
defined('DB_VERIFY')				OR define('DB_VERIFY', $_SERVER['DB_VERIFY'] ?? 'ON');

/*
|--------------------------------------------------------------------------
| APPLICATION SETTINGS
|--------------------------------------------------------------------------
*/
defined('ERROR_CONTROLLER')		OR define('ERROR_CONTROLLER', $errorController);
defined('ACTIVE_SAFETY')		OR define('ACTIVE_SAFETY', $_SERVER['ACTIVE_SAFETY']);
defined('CYPHER_BASE')			OR define('CYPHER_BASE', $_SERVER['CYPHER_BASE']);
defined('ACCESS_URL')				OR define('ACCESS_URL', $_SERVER['ACCESS_URL']);
defined('ACTIVE_RECAPTCHA')	OR define('ACTIVE_RECAPTCHA', $_SERVER['ACTIVE_RECAPTCHA'] == 'ON' ? TRUE : FALSE);
defined('LANGUAGE')					OR define('LANGUAGE', BASE_LANGUAGE === 'english' ? 'en' : 'es');
defined('IP_VERIFY')				OR define('IP_VERIFY', $_SERVER['IP_VERIFY'] ?? 'ON');
defined('SINGLE_SIGN_ON')		OR define('SINGLE_SIGN_ON', $_SERVER['SINGLE_SIGN_ON'] == 'ON' ? TRUE : FALSE);
defined('ASSET_PATH')				OR define('ASSET_PATH', $_SERVER['ASSET_PATH']);
defined('API_CONTENT_URL')	OR define('API_CONTENT_URL', $_SERVER['API_CONTENT_URL']);
defined('UPLOAD_PATH')			OR define('UPLOAD_PATH', $_SERVER['UPLOAD_PATH']);

/*
|--------------------------------------------------------------------------
| SERVICE ENVIROMENT VARIABLES
|--------------------------------------------------------------------------
*/
defined('WS_KEY')			 						OR define('WS_KEY', $_SERVER['WS_KEY']);
defined('DOWNLOAD_ROUTE')					OR define('DOWNLOAD_ROUTE', $_SERVER['DOWNLOAD_ROUTE']);
defined('BULK_FTP_USERNAME')			OR define('BULK_FTP_USERNAME', $_SERVER['BULK_FTP_USERNAME']);
defined('BULK_FTP_PASSWORD')			OR define('BULK_FTP_PASSWORD', $_SERVER['BULK_FTP_PASSWORD']);
defined('BULK_FTP_URL')						OR define('BULK_FTP_URL', $_SERVER['BULK_FTP_URL']);
defined('API_URL')								OR define('API_URL', $_SERVER['API_URL']);
defined('SERVICE_URL')						OR define('SERVICE_URL', $_SERVER['SERVICE_URL']);
defined('SERVICE_CLIENT_ID')			OR define('SERVICE_CLIENT_ID', $_SERVER['SERVICE_CLIENT_ID']);
defined('SERVICE_CLIENT_SECRET')	OR define('SERVICE_CLIENT_SECRET', $_SERVER['SERVICE_CLIENT_SECRET']);

unset($uriSegments, $proxyIps, $timeZone, $baseLanguage, $errorController);
