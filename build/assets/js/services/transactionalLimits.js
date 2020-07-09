'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$('.slide-slow').click(function() {
		$(this).next(".section").slideToggle("slow");
		$(".help-block").text("");
	});
	$('.money').mask('000.000.000.000.000,00', {reverse: true});
})
