<?php
function insert_js($filename = '') {
	$fileurl = BASE_CDN_URL . 'js/' . $filename;
	$filepath = BASE_CDN_PATH . 'js/' . $filename;
	$version = '';
	if (file_exists($filepath)) {
		$version = '?v=' . date('Ymd-U', filemtime($filepath));
	}

	$js = '<script src="' . $fileurl . $version . '" type="text/javascript"></script>' . "\n";
	return $js;
}


function insert_css($filename = '', $media = 'screen') {
	$fileurl = BASE_CDN_URL . 'css/' . $filename;
	$filepath = BASE_CDN_PATH . 'css/' . $filename;
	$version = '';
	if (file_exists($filepath)) {
		$version = '?v=' . date('Ymd-U', filemtime($filepath));
	}

	$css = '<link href="' . $fileurl . $version .  '" media="' . $media . '" rel="stylesheet" type="text/css" />' . "\n";
	return $css;
}


function insert_favicon($country) {
	$fileext = 'png';
	$filetype = 'image/png';

	if ($country === 'Ec-bp') {
		$fileext = 'ico';
		$filetype = 'image/icon';
	}

	$fileurl = BASE_CDN_URL . $country . '/media/img/favicon.' . $fileext;

	$favicon = '<link href="' . $fileurl . '" rel="icon" type="' . $filetype . '">';
	return $favicon;
}


function get_country() {
	if (isset($_SERVER['REQUEST_URI'])) {
		$uri = str_replace(BASE_URL, '/', $_SERVER['REQUEST_URI']);
		$uri_segments = explode('/', $uri);
		$uri_country = $uri_segments[1];
	} else {
		$uri_country = 'Pe';
	}

	switch (strtolower($uri_country)) {
		case 'co':
			$country = 'Co';
			break;
		case 'ec-bp':
		case 'bp':
			$country = 'Ec-bp';
			break;
		case 'usd':
		case 'us':
			$country = 'Usd';
			break;
		case 've':
			$country = 'Ve';
			break;
		default:
			$country = 'Pe';
			break;
	}

	return $country;
}
?>
