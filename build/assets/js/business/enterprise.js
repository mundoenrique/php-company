'use strict'
var contar = false;
$(function () {
	var filterPage;
	var enterpriseListEvent = $('#enterprise-list');
	var enterprisePages = $('#enterprise-pages');
	var noEnterprise = $('#no-enterprise');
	var alphabetical = $('#alphabetical');
	var showPage = $('#show-page');

	//external js: isotope.pkgd.js
	var enterpriseList = enterpriseListEvent.isotope({
		itemSelector: '.card',
		layoutMode: 'masonry',
    masonry: {
      columnWidth: 270,
      isFitWidth: true,
      gutter: 16
		},
		initLayout: false
	});

	enterpriseList.one('arrangeComplete', function(event, filteredItems) {
		if(filteredItems.length > 0) {
			noEnterprise.addClass('none');
			$('#alphabetical > button:first').addClass('current-outline');
			$('#show-page > span:first').addClass('page-current');
			$(event.currentTarget).removeClass('visible');
			enterprisePages.removeClass('visible');
		} else {
			noEnterprise.find('span').text(lang.ENTERPRISE_NOT_ASSIGNED);
		}
	});

	enterpriseList.on('arrangeComplete', function(event, filteredItems) {
		/*if(filteredItems.length < 4) {
			enterpriseListEvent.removeClass('mx-auto')
		} else {
			enterpriseListEvent.addClass('mx-auto')
		}*/
	});

	enterpriseList.isotope({filter: '.page_1'});

	alphabetical.on('click', 'button', function(e) {
		e.preventDefault();
		$(this).parent().children().removeClass('current-outline');
		$(this).addClass('current-outline');
		filterPage = $(this).attr('filter-page');
		if(filterPage) {
			orderPage(filterPage, 1);
			paginateList(filterPage);
			enterpriseList.isotope({ filter: '.'+filterPage });
		}
	});

	enterprisePages.on('click', function(e) {
		e.preventDefault();
		var event = $(e.target)
		var position = event.attr('position');
		var fast = (position == 'minus' || position == 'plus')
		if(!fast && e.target.tagName == 'A') {
			var paginateEvent = {
				targetEvent: event,
				pagesTotal: parseInt($(this).find('#show-page > span').length),
				currentPage: parseInt($(this).find('.page-current').children().text()),
				currentFilter: $(this).find('.page-current').children().attr('filter-page'),
			}
			renderpage[position](paginateEvent);
		}
	});

	const renderpage = {
		page: function(paginateEvent) {
			var currentFilter = paginateEvent.currentFilter + paginateEvent.currentPage;
			filterPage = paginateEvent.targetEvent.attr('filter-page')+paginateEvent.targetEvent.text();
			if(currentFilter != filterPage) {
				orderPage(filterPage, paginateEvent.targetEvent.text());
			}
		},
		next: function(paginateEvent) {
			var newPage = paginateEvent.currentPage + 1 > paginateEvent.pagesTotal ?
			paginateEvent.pagesTotal : paginateEvent.currentPage + 1;
			filterPage = paginateEvent.currentFilter + newPage
			if(paginateEvent.currentPage < paginateEvent.pagesTotal) {
				orderPage(filterPage, newPage);
			}
		},
		prev: function(paginateEvent) {
			var newPage = paginateEvent.currentPage - 1 < 1 ? paginateEvent.currentPage : paginateEvent.currentPage - 1;
			filterPage = paginateEvent.currentFilter + newPage;
			if(newPage < paginateEvent.currentPage) {
				orderPage(filterPage, newPage);
			}
		},
		last: function(paginateEvent) {
			filterPage = paginateEvent.currentFilter + paginateEvent.pagesTotal;
			if(paginateEvent.currentPage < paginateEvent.pagesTotal) {
				orderPage(filterPage, paginateEvent.pagesTotal);
			}
		},
		first: function(paginateEvent) {
			filterPage = paginateEvent.currentFilter + '1';
			if(paginateEvent.currentPage > 1) {
				orderPage(filterPage, '1');
			}
		}
	}

	$('#enterprise-pages a[position="plus"]').on('mouseover', function() {
		contar = true;
		console.log('over')
		pasarpage(1, 1000, 'casa_')
	});

	$('#enterprise-pages a[position="plus"]').on('mouseleave', function() {
		contar = false;
		console.log('leave')
	});

	var orderPage = function(filterPage, newpage) {
		var reg = $('.'+filterPage).length;
		if(reg < 5) {
			$('#enterprise-list').removeClass('mx-auto');
		} else {
			$('#enterprise-list').addClass('mx-auto');
		}
		enterprisePages.find('span').removeClass('page-current');
		enterprisePages.find(`span > a:contains(${newpage})`).parent().addClass('page-current');;
		enterpriseList.isotope({ filter: '.'+filterPage });
	}

	var paginateList = function(filterOrder) {
		showPage.children().remove();
		filterOrder = filterOrder.split('_')[0];
		var classU = '';
		var page = 1, classElement, init, finish, substr;
		enterpriseListEvent.children('div.card').each(function(pos, element) {
			classElement = $(element).attr('class');
			init = classElement.indexOf(filterOrder);
			finish = (init + filterOrder.length);
			substr = classElement.substring(init, finish);
			classElement = classElement.substring(init, finish + 2);
			if(filterOrder == substr && classU !== classElement) {
				classU = classElement;
				showPage.append(`<span class="mx-1"><a href="javascript:" position="page" filter-page="${filterOrder}_">${page}</a></span>`);
				page++;
			}
		})
		$('#show-page > span:first').addClass('page-current');
	};

	$('.product').hover(function() {
		if ($(this).find('span.danger').length) {
			$(this).css('cursor', 'default')
		}
	});

	$('.product').on('click', function() {
		var getProducts = $('#get_products')
		var totalProduct = parseInt($(this).find('.total-product').text());
		var EnterpriseName = $(this).find('.enterprise-name').text();
		var idFiscal = $(this).find('.id-fiscal').text();
		if(totalProduct > 0) {
			$(this).off('click');
			noEnterprise
			.removeClass('none')
			.children('div')
			.html(loader)
			.find('span')
			.removeClass('secondary')
			.addClass('spinner-border-lg primary');
			enterpriseList.addClass('none');
			enterprisePages.addClass('none');
			idFiscal = idFiscal.replace(lang.GEN_FISCAL_REGISTRY, '');
			getProducts.append(`<input type="hidden" name="idFiscal" value="${idFiscal}">`);
			getProducts.append(`<input type="hidden" name="enterpriseName" value="${EnterpriseName}">`);
			getProducts.submit();
		}
	});
});

function pasarpage(current, total, filtro)
{
	var paginateEvent = {
		pagesTotal: parseInt($(this).find('#show-page > span').length),
		currentPage: parseInt($(this).find('.page-current').children().text()),
		currentFilter: $(this).find('.page-current').children().attr('filter-page'),
	}
	if(current <= total && contar)
	{
		console.log(current++, filtro+current)
		pasarpage(current, total, filtro)

	}

}
