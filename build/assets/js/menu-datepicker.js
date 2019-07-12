'use strict'
$(function() {
	$.balloon.defaults.css = null;
	$('#config').balloon({
		html: true,
		position: 'bottom', 
		classname: 'config-menuH',
		css: {
			color: 'rgb(102, 102, 102)'
		},
		tipSize: 0,
		contents: $('.submenu')
	});
});
