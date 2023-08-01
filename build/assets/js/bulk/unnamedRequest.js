'use strict'
$(function () {
	var unnamedReqBtn = $('#unnamed-request-btn');
	var maxCards = $('#maxCards');
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	$('#password').on('keyup', function() {
		$(this).attr('type', 'password')
	})
	$('#maxCards').keyup(function (){
		if(this.value == 0){
			this.value = (this.value + '').replace(0, '');
		}
	});

	unnamedReqBtn.on('click', function(e) {
		e.preventDefault();
		var unnamedBtn = $(this);
		form = $('#unnamed-request-form');
		btnText = unnamedBtn.text().trim();
		validateForms(form)

		if (form.valid()) {
			data = getDataForm(form);

			if (lang.SETT_REMOTE_AUTH == 'OFF') {
				data.password = cryptoPass(data.password);
			}

			insertFormInput(true, form);
			unnamedBtn.html(loader);
			unnamedRequest(unnamedBtn);
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

function unnamedRequest(unnamedBtn) {
	who = 'Bulk';
	where = 'UnnamedRequest'

	callNovoCore(who, where, data, function(response) {
		insertFormInput(false)
		unnamedBtn.text(btnText);
	});
}
