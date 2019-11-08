'use strict'
var offset = new Date().getTimezoneOffset();
console.log(offset);
$(function () {
	var filterPage;

	//external js: isotope.pkgd.js
	var enterpriseList = $('#enterprise-list').isotope({
		itemSelector: '.card',
		layoutMode: 'masonry',
    masonry: {
      columnWidth: 270,
      isFitWidth: true,
      gutter: 16
    }
	});

	enterpriseList.isotope({ filter: '.page_1' }).removeClass('visible');

	$('#show-page').on('click', 'a', function(e) {
		filterPage = $(this).attr('filter-page');
		enterpriseList.isotope({ filter: '.'+filterPage });
	});

	$('#alphabetical').on('click', 'button', function(e) {
		$(this).parent().children().removeClass('current-outline');
		$(this).addClass('current-outline');
		filterPage = $(this).attr('filter-page');
		orderPages(filterPage);
		enterpriseList.isotope({ filter: '.'+filterPage });
	});

	var orderPages = function(filterOrder) {
		var shwoPage = $('#show-page');
		shwoPage.children().remove();
		filterOrder = filterOrder.split('_')[0];
		var classU = '';
		var page = 1, classElement, init, finish, substr;
		$('#enterprise-list').children('div.card').each(function(pos, element) {
			classElement = $(element).attr('class');
			init = classElement.indexOf(filterOrder);
			finish = (init + filterOrder.length);
			substr = classElement.substring(init, finish);
			classElement = classElement.substring(init, finish + 2);

			if(filterOrder == substr && classU !== classElement) {
				classU = classElement;
				shwoPage.append('<span><a href="javascript:" filter-page="'+classU+'">'+page+'</a></span>');
				page++;
			}
		})
	};
});
