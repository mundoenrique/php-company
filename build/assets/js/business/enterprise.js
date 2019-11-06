'use strict'
$(function () {
	var filterPage;

	//external js: isotope.pkgd.js
	var enterpriseList = $('#enterprise-list').isotope({
		itemSelector: '.card',
		layoutMode: 'fitRows'
	});

	enterpriseList.isotope({ filter: '.page_1' });

	function onArrange() {
		console.log('arrange done');
	}
	// bind event listener
	enterpriseList.on( 'arrangeComplete', onArrange );
	// un-bind event listener
	enterpriseList.off( 'arrangeComplete', onArrange );
	// bind event listener to be triggered just once. note ONE not ON
	enterpriseList.one( 'arrangeComplete', function() {
		console.log('just this one time');
		$('#enterprise-list').removeClass('none')
	});

	$('#show-page').on('click', 'a', function(e) {
		filterPage = $(this).attr('filter-page');
		enterpriseList.isotope({ filter: '.'+filterPage });
	});

	$('#alphabetical').on('click', 'button', function(e) {
		filterPage = $(this).attr('filter-page');
		orderPages(filterPage);
		enterpriseList.isotope({ filter: '.'+filterPage });
	});

	var orderPages = function(filterOrder) {
		var shwoPage = $('#show-page');
		shwoPage.children().remove();
		filterOrder = filterOrder.split('_')[0];
		var classU = '';
		var page = 1;
		$('#enterprise-list').children('div.card').each(function(pos, element) {
			var classElement = $(element).attr('class');
			var init = classElement.indexOf(filterOrder);
			var finish = (init + filterOrder.length);
			var substr = classElement.substring(init, finish);
			classElement = classElement.substring(init, finish + 2)

			if(filterOrder == substr && classU !== classElement) {
				classU = classElement;
				shwoPage.append('<span><a href="javascript:" filter-page="'+classU+'">'+page+'</a></span>');
				page++;
			}
		})
	};

	//external js: pagination.js
	$('.pagination').pagination({
		dataSource: function(done){
			var result = [];
			for (var i = 1; i <= 15; i++) {
					result.push(i);
			}
			console.log(result)
			done(result);
	 },
	 pageRange: 2,
	 pageSize: 4
	})
});
