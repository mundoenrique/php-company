'use strict'
$(function() {
	var productDetail = $('.product-detail');


	productDetail.on('click', function(e) {
		e.preventDefault();
		var getProductDetail = $(this).parents('.select-product').find('form').attr('id');
		getProductDetail = $('#'+getProductDetail);
		$(this).html(loader);
		$(this).find('span').addClass('spinner-border-sm');
		insertFormInput(getProductDetail);
		getProductDetail.submit();
	});
});
