'use strict'
$(function() {
	var productDetail = $('.product-detail');
	var getProductDetail  = $('#get_product_detail');
	productDetail.on('click', function(e) {
		e.preventDefault();
		$(location).attr('href', baseURL+'detalle-producto')
	});
});
