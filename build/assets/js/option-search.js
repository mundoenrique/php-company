'use strict';
var set = $('.card, .products');
var searchIcon = $('.sb-icon-search');
var searchBox = $('.sb-search');
var searchInput = $('.sb-search-input');

/* Search bars
   ========================================================================== */

$(document).click(function () {
	searchBox.removeClass('sb-search-open');
	searchInput.val('')
});

searchBox.click(function () {
	event.stopPropagation();
});

searchIcon.click(function () {
	event.stopPropagation();
	searchBox.toggleClass('sb-search-open');
});
