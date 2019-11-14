'use strict';
var set = $('.card, .products');
var searchIcon = $('.sb-icon-search');
var searchBox = $('.sb-search');
var searchInput = $('.sb-search-input');

/* Search bars
   ========================================================================== */

$(document).click(function () {
	searchBox.removeClass('sb-search-open');
});

searchBox.click(function () {
	event.stopPropagation();
});

searchIcon.click(function () {
	event.stopPropagation();
	searchBox.toggleClass('sb-search-open');
});

searchInput.keyup(function () {
	var valux = $(this).val();
	valux = $.trim(valux).length;
	if (valux !== 0) {
		console.log("algo que buscar");
	} else {
		console.log("nada que buscar");
	}
});
