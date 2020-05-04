'use strict'
$(function () {
	var unnamedReqBtn = $('#unnamed-request-btn');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	unnamedReqBtn.on('click', function(e) {
		e.preventDefault();
		var unnamedBtn = $(this);
		form = $('#unnamed-request-form');
		btnText = unnamedBtn.text().trim();
		validateForms(form)

		if (form.valid()) {

		}
	});
});
