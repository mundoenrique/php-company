'use strict'
$(function() {
	form = $('#single-signin-form');
	var send = $('#single-signin-form').attr('send');

	if(send) {
		insertFormInput(true, form);
		form.submit()
	} else {
		who = 'User';
		where = 'singleSignOn';
		data = getDataForm(form)
		data.currentTime = new Date().getHours();

		callNovoCore(who, where, data, function (response) {
			$(location).attr('href', response.data)
		})
	}
})
