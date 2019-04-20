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

define('API_URL', $_SERVER['API_URL']);
define('API_CONTENT_URL', $_SERVER['API_CONTENT_URL']);

define('SERVICE_URL', $_SERVER['SERVICE_URL']);
define('SERVICE_CLIENT_ID', $_SERVER['SERVICE_CLIENT_ID']);
define('SERVICE_CLIENT_SECRET', $_SERVER['SERVICE_CLIENT_SECRET']);

define('BULK_FTP_URL', $_SERVER['BULK_FTP_URL']);
define('BULK_FTP_USERNAME', $_SERVER['BULK_FTP_USERNAME']);
define('BULK_FTP_PASSWORD', $_SERVER['BULK_FTP_PASSWORD']);
define('BULK_LOCAL_PATH', $_SERVER['BULK_LOCAL_PATH']);

define('ENCRYPTION_KEY', isset($_SERVER['ENCRYPTION_KEY']) ?
	$_SERVER['ENCRYPTION_KEY'] : 'n0v0p4ym3nt'
);
define('SESS_COOKIE_NAME', isset($_SERVER['SESS_COOKIE_NAME']) ?
	$_SERVER['SESS_COOKIE_NAME'] : 'ceo_session'
);
define('SESS_EXPIRATION', isset($_SERVER['SESS_EXPIRATION'])
&& filter_var($_SERVER['SESS_EXPIRATION'], FILTER_VALIDATE_INT) ?
	intval($_SERVER['SESS_EXPIRATION']) : 7200
);
define('COOKIE_PREFIX', isset($_SERVER['COOKIE_PREFIX']) ?
	$_SERVER['COOKIE_PREFIX'] : 'ceo_'
);
define('COOKIE_DOMAIN', isset($_SERVER['COOKIE_DOMAIN']) ?
	$_SERVER['COOKIE_DOMAIN'] : ''
);
define('COOKIE_PATH', isset($_SERVER['COOKIE_PATH']) ?
	$_SERVER['COOKIE_PATH'] : '/'
);
define('COOKIE_SECURE', isset($_SERVER['COOKIE_SECURE'])
&& filter_var($_SERVER['COOKIE_SECURE'], FILTER_VALIDATE_BOOLEAN) ?
	boolval($_SERVER['COOKIE_SECURE']) : FALSE
);

$arrayUri = explode('/', $_SERVER['REQUEST_URI']);
$lang = end($arrayUri);

define('LANGUAGE', $lang === 'en' ? 'en' : 'es');

unset($arrayUri, $lang);


/* End of file constants.php */
/* Location: ./application/config/constants.php */
