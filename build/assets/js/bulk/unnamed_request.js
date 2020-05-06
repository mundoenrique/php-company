'use strict'
$(function () {
	var unnamedReqBtn = $('#unnamed-request-btn');
	var maxCards = $('#maxCards');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	unnamedReqBtn.on('click', function(e) {
		e.preventDefault();
		var unnamedBtn = $(this);
		form = $('#unnamed-request-form');
		btnText = unnamedBtn.text().trim();
		validateForms(form)

		if (form.valid()) {
			data = getDataForm(form);
			data.password = cryptoPass(data.password);
			insertFormInput(true, form);
			unnamedBtn.html(loader);
			unnamedRequest();
		} else {
			if (maxCards.hasClass('has-error')) {
				var totalCards = parseInt(maxCards.attr('max-cards'));

				if(totalCards > 0) {
					var text = maxCards.siblings('.help-block').text();
					text+= ', '+lang.VALIDATE_MAXIMUM+' '+totalCards;
					maxCards.siblings('.help-block').text(text);
				}
			}
		}
	});
});

function unnamedRequest() {
	verb = 'POST'; who = 'Bulk'; where = 'UnnamedRequest'
	callNovoCore(verb, who, where, data, function(response) {

	});
}
