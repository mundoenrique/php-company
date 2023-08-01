'use strict';
var set = $('.card, .products');
var searchIcon = $('.sb-icon-search');
var searchBox = $('.sb-search');
var searchInput = $('.sb-search-input');

$(document).click(function () {
	searchBox.removeClass('sb-search-open');
	searchInput.val('')
});

searchBox.click(function (e) {
	e.stopPropagation();
});

searchIcon.click(function (e) {
	e.stopPropagation();
	searchBox.toggleClass('sb-search-open');
});

