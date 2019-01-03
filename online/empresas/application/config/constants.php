<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/http://180.180.159.26:8005

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');
define('CDN_PATH', '/opt/httpd-2.4.4/vhost/cdn/');
//define('URL_TEMPLOTES', 'sftp://180.180.159.22:22/opt/tomcat/TempLotes/');
define('URL_TEMPLOTES', 'sftp://180.180.159.26:22/u01/app/data/lotes/temp/');

define('MAX_FIELD_LENGTH', 15);

/*
|--------------------------------------------------------------------------
| Environment variables
|--------------------------------------------------------------------------
|
| Constants expected as environment variables on the instance to be used
| as part of global configuration settings.
|
*/
define('BASE_URL', $_SERVER['BASE_URL']);
define('BASE_CDN_URL', $_SERVER['BASE_CDN_URL']);
define('BASE_CDN_PATH', $_SERVER['BASE_CDN_PATH']);
define('WS_URL', $_SERVER['WS_URL']);
define('WS_KEY', $_SERVER['WS_KEY']);
define('ENCRYPTION_KEY', $_SERVER['ENCRYPTION_KEY']);
define('SESS_COOKIE_NAME', $_SERVER['SESS_COOKIE_NAME']);
define('SESS_EXPIRATION', $_SERVER['SESS_EXPIRATION']);
define('COOKIE_PREFIX', $_SERVER['COOKIE_PREFIX']);
define('COOKIE_PATH', $_SERVER['COOKIE_PATH']);
define('COOKIE_SECURE', $_SERVER['COOKIE_SECURE']);


/* End of file constants.php */
/* Location: ./application/config/constants.php */
