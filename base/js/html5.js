// Enables HTML5 support for IE outdated browsers
if ( navigator.appName == 'Microsoft Internet Explorer' ) {
	var ua = new RegExp("MSIE ([0-8]{1,}[\.0-9]{0,})");
	if (ua.exec(navigator.userAgent) != null) {
		var version = parseFloat(RegExp.$1);
		
		if (version <= 8.0) {
			var html5els = ['article', 'aside', 'audio', 'canvas', 'footer', 'header', 'hgroup', 'nav', 'section', 'source', 'time', 'video'];
			
			for (var j = 0; j < html5els.length; j++) {
				document.createElement(html5els[j]);
			}
		}
	}
}
