<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

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
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION VARIABLES
|--------------------------------------------------------------------------
*/
$db_port = (isset($_SERVER['DB_PORT'])) ? intval($_SERVER['DB_PORT']) : NULL;
defined('DB_HOSTNAME')    or define('DB_HOSTNAME', $_SERVER['DB_HOSTNAME'] ?? NULL);
defined('DB_PORT')        or define('DB_PORT', $db_port);
defined('DB_USERNAME')    or define('DB_USERNAME', $_SERVER['DB_USERNAME'] ?? NULL);
defined('DB_PASSWORD')    or define('DB_PASSWORD', $_SERVER['DB_PASSWORD'] ?? NULL);
defined('DB_DRIVER')      or define('DB_DRIVER', $_SERVER['DB_DRIVER'] ?? 'mysqli');
defined('DB_CHARSET')     or define('DB_CHARSET', $_SERVER['DB_CHARSET'] ?? 'utf8');
defined('DB_COLLATION')   or define('DB_COLLATION', $_SERVER['DB_COLLATION'] ?? 'utf8_general_ci');
defined('DB_VERIFY')      or define('DB_VERIFY', $_SERVER['DB_VERIFY'] === 'ON' ? TRUE : FALSE);

/*
|--------------------------------------------------------------------------
| Environment variables
|--------------------------------------------------------------------------
|
| Constants expected as environment variables on the instance to be used
| as part of global configuration settings.
|
*/
$uriSegments  =  explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$timeZone = [
  'bdb' => 'America/Bogota',
  'bg'  => 'America/Guayaquil',
  'bnt' => 'America/Mexico_City',
  'bp'  => 'America/Guayaquil',
  'col' => 'America/Bogota',
  'pb'  => 'America/Guayaquil',
  'pe'  => 'America/Lima',
  'us'  => 'America/Lima',
  'ven' => 'America/Caracas',
  'vg'  => 'America/Lima',
];
$errorController = array_key_exists($uriSegments[1], $timeZone) ? 'Novo_Errors/pageNoFound' : '';
$timeZone = array_key_exists($uriSegments[1], $timeZone) ? $timeZone[$uriSegments[1]] : 'America/New_York';
date_default_timezone_set($timeZone);

/*
|--------------------------------------------------------------------------
| FRAMEWORK SETTINGS
|--------------------------------------------------------------------------
*/
defined('BASE_URL')           or define('BASE_URL', $_SERVER['BASE_URL']);
defined('ASSET_URL')          or define('ASSET_URL', $_SERVER['ASSET_URL']);
defined('ASSET_PATH')         or define('ASSET_PATH', $_SERVER['ASSET_PATH']);
defined('THRESHOLD')          or define('THRESHOLD', $_SERVER['CI_ENV'] === 'development' ? 4 : 2);
defined('LOG_PATH')           or define('LOG_PATH', $_SERVER['LOG_PATH'] ?? '');
defined('ENCRYPTION_KEY')     or define('ENCRYPTION_KEY', $_SERVER['ENCRYPTION_KEY'] ?? '3NCRYPT10N');
defined('SESS_DRIVER')        or define('SESS_DRIVER', $_SERVER['SESS_DRIVER'] ?? 'files');
defined('SESS_COOKIE_NAME')   or define('SESS_COOKIE_NAME', $_SERVER['SESS_COOKIE_NAME'] ?? 'session');
defined('SESS_EXPIRATION')    or define('SESS_EXPIRATION', intval($_SERVER['SESS_EXPIRATION']));
defined('SESS_SAVE_PATH')     or define('SESS_SAVE_PATH', $_SERVER['SESS_SAVE_PATH'] ?? NULL);
defined('COOKIE_PREFIX')      or define('COOKIE_PREFIX', $_SERVER['COOKIE_PREFIX']);
defined('COOKIE_DOMAIN')      or define('COOKIE_DOMAIN', $_SERVER['COOKIE_DOMAIN']);
defined('COOKIE_SECURE')      or define('COOKIE_SECURE', $_SERVER['COOKIE_SECURE']);
defined('PROXY_IPS')          or define('PROXY_IPS', $_SERVER['PROXY_ENABLE'] === 'ON' ? $_SERVER['REMOTE_ADDR'] : '');

/*
|--------------------------------------------------------------------------
| APPLICATION SETTINGS
|--------------------------------------------------------------------------
*/
defined('CUSTUMER_OLD_WAY')   or define('CUSTUMER_OLD_WAY', 'bpi|col|per|usd|ve');
defined('CUSTUMER_DENY_WAY')  or define('CUSTUMER_DENY_WAY', explode('|', CUSTUMER_OLD_WAY));
defined('ERROR_CONTROLLER')   or define('ERROR_CONTROLLER', $errorController);
defined('ACTIVE_SAFETY')      or define('ACTIVE_SAFETY', $_SERVER['ACTIVE_SAFETY'] === 'ON' ? TRUE : FALSE);
defined('CYPHER_BASE')        or define('CYPHER_BASE', $_SERVER['CYPHER_BASE']);
defined('ACCESS_URL')         or define('ACCESS_URL', $_SERVER['ACCESS_URL']);
defined('ACTIVE_RECAPTCHA')   or define('ACTIVE_RECAPTCHA', $_SERVER['ACTIVE_RECAPTCHA'] === 'ON' ? TRUE : FALSE);
defined('IP_VERIFY')          or define('IP_VERIFY', $_SERVER['IP_VERIFY'] === 'ON' ? TRUE : FALSE);
defined('SINGLE_SIGN_ON')     or define('SINGLE_SIGN_ON', $_SERVER['SINGLE_SIGN_ON'] === 'ON' ? TRUE : FALSE);
defined('API_CONTENT_URL')    or define('API_CONTENT_URL', $_SERVER['API_CONTENT_URL']);
defined('UPLOAD_PATH')        or define('UPLOAD_PATH', $_SERVER['UPLOAD_PATH']);

/*
|--------------------------------------------------------------------------
| SERVICE ENVIROMENT VARIABLES
|--------------------------------------------------------------------------
*/
defined('WS_KEY')                 or define('WS_KEY', $_SERVER['WS_KEY']);
defined('DOWNLOAD_ROUTE')         or define('DOWNLOAD_ROUTE', $_SERVER['DOWNLOAD_ROUTE']);
defined('BULK_FTP_USERNAME')      or define('BULK_FTP_USERNAME', $_SERVER['BULK_FTP_USERNAME']);
defined('BULK_FTP_PASSWORD')      or define('BULK_FTP_PASSWORD', base64_decode($_SERVER['BULK_FTP_PASSWORD']));
defined('BULK_FTP_URL')           or define('BULK_FTP_URL', $_SERVER['BULK_FTP_URL']);
defined('API_URL')                or define('API_URL', $_SERVER['API_URL']);
defined('SERVICE_URL')            or define('SERVICE_URL', $_SERVER['SERVICE_URL']);
defined('SERVICE_CLIENT_ID')      or define('SERVICE_CLIENT_ID', $_SERVER['SERVICE_CLIENT_ID']);
defined('SERVICE_CLIENT_SECRET')  or define('SERVICE_CLIENT_SECRET', $_SERVER['SERVICE_CLIENT_SECRET']);

unset($uriSegments, $timeZone, $errorController);
