'use strict'
$(function () {
	var filterPage;
	var enterpriseListEvent = $('#enterprise-list');
	var enterprisePages = $('#enterprise-pages');
	var noEnterprise = $('#no-enterprise');

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
			noEnterprise.children('div>span').text(lan.ENTERPRISE_NOT_ASSIGNED)
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

	$('#show-page').on('click', 'a', function(e) {
		$(this).parents().children().removeClass('page-current');
		$(this).parent().addClass('page-current');
		filterPage = $(this).attr('filter-page');
		orderPage(filterPage);
		enterpriseList.isotope({ filter: '.'+filterPage });
	});

	$('#alphabetical').on('click', 'button', function(e) {
		$(this).parent().children().removeClass('current-outline');
		$(this).addClass('current-outline');
		filterPage = $(this).attr('filter-page');
		if(filterPage) {
			orderPage(filterPage);
			paginateList(filterPage);
			enterpriseList.isotope({ filter: '.'+filterPage });
		}
	});

	var orderPage = function(filter) {
		var reg = $('.'+filter).length;
		if(reg < 5) {
			$('#enterprise-list').removeClass('mx-auto');
		} else {
			$('#enterprise-list').addClass('mx-auto');
		}
	}

	var paginateList = function(filterOrder) {
		var shwoPage = $('#show-page');
		shwoPage.children().remove();
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
				shwoPage.append(`<span class="mx-1"><a href="javascript:" filter-page="${classU}">${page}</a></span>`);
				page++;
			}
		})
		$('#show-page > span:first').addClass('page-current');
	};
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
