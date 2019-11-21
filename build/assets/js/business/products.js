'use strict'
$(function() {
	var productDetail = $('.product-detail');
	var getProductDetail  = $('#get_product_detail');
	productDetail.on('click', function(e) {
		e.preventDefault();
		insertFormInput();
		$(this).html(loader);
		$(this).find('span').addClass('spinner-border-sm');
		$(location).attr('href', baseURL+'detalle-producto')
	});
});
