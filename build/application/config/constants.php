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

define('API_URL', $_SERVER['API_URL']);
define('SERV_URL', $_SERVER['SERV_URL']);
define('CLIENT_ID', $_SERVER['CLIENT_ID']);
define('CLIENT_SECRET', $_SERVER['CLIENT_SECRET']);
define('LOTES_USERPASS', $_SERVER['LOTES_USERPASS']);
define('URL_TEMPLOTES', $_SERVER['URL_TEMPLOTES']);
define('FOLDER_UPLOAD_LOTES', $_SERVER['FOLDER_UPLOAD_LOTES']);
define('API_CONTENT', $_SERVER['API_CONTENT']);

define('BASE_CDN_URL', $_SERVER['BASE_CDN_URL']);
define('BASE_CDN_PATH', $_SERVER['BASE_CDN_PATH']);
define('WS_URL', $_SERVER['WS_URL']);
define('WS_KEY', $_SERVER['WS_KEY']);
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
define('COOKIE_PATH', isset($_SERVER['COOKIE_PATH']) ?
	$_SERVER['COOKIE_PATH'] : '/'
);
define('COOKIE_SECURE', isset($_SERVER['COOKIE_SECURE'])
&& filter_var($_SERVER['COOKIE_SECURE'], FILTER_VALIDATE_BOOLEAN) ?
	boolval($_SERVER['COOKIE_SECURE']) : FALSE
);

define('COOKIE_DOMAIN', isset($_SERVER['COOKIE_DOMAIN']) ?
	$_SERVER['COOKIE_DOMAIN'] : ''
);


/* End of file constants.php */
/* Location: ./application/config/constants.php */
