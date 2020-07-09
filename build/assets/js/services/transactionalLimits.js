'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('.slide-slow').click(function() {
		$(this).next(".section").slideToggle("slow");
		$(".help-block").text("");
	});
	$('input').on('input', function () {
    this.value = this.value.replace(/[^0-9]/g,'');
	});
})
