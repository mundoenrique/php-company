'use strict'
$(function() {
	form = $('#single-signin-form');
	var send = $('#single-signin-form').attr('send');

	if(send) {
		insertFormInput(true, form);
		form.submit()
	} else {
		data = getDataForm(form)
		data.currentTime = new Date().getHours();
		verb = 'POST'; who = 'User'; where = 'singleSignOn';
		callNovoCore(verb, who, where, data, function (response) {
			$(location).attr('href', response.data)
		})
	}
})
