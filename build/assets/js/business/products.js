'use strict'
$(function() {
	var productDetail = $('.product-detail');
	var noProduct = $('#no-product');
	var productList = $('#product-list');

	jplist.init();
	resetPaginator($('#pagination-control'));

	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	if(code > 2) {
		noProduct.removeClass('none');
		productList.addClass('none');
	}

	productDetail.on('click', function(e) {
		e.preventDefault();
		var getProductDetail = $(this).parents('.select-product').find('form').attr('id');
		getProductDetail = $('#'+getProductDetail);
		$(this).html(loader);
		$(this).find('span').addClass('spinner-border-sm');
		insertFormInput(true, getProductDetail);
		searchEnterprise
		.off('click')
		.addClass('sb-disabled');
		getProductDetail.submit();
	});

	$('#resetBtn').on('click', function (e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		resetPaginator($('#pagination-control'));
	});
});

function resetPaginator(element) {
	if (!element.hasClass("jplist-pages-number-1")) {
		element.addClass('flex');
		element.removeClass('hide');
	}
}
