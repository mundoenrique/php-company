$(function() {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false)

	$('#btn-pass-recover').on('click', function(e){
		e.preventDefault();
		var recoverBtn = $(this);
		form = $('#form-pass-recovery');
		validateForms(form, {handleMsg: true});
		if(form.valid()) {
			var textBtn = recoverBtn.text();
			var recoverData = {
				user: $('#user-name').val(),
				idEmpresa: $('#id-company').val(),
				email: $('#email').val()

			}
			$('form input, form button').attr('disabled', true);
			recoverBtn.html(loader);
			getRecaptchaToken('recoverPass', function (recaptchaToken) {
				recoverData.token = recaptchaToken;
				passRecover(recoverData, textBtn);
			});
		}
	});
})

function passRecover(recoverData, textBtn) {
	who = 'User';
	where = 'RecoverPass';
	data = recoverData;

	callNovoCore(who, where, data, function(response){
		dataResponse = response.data
		switch(response.code) {
			case 0:
			case 1:
				appMessages(response.title, response.msg, response.icon, response.modalBtn)
			break;
		}
		response.code == 0 ? $('form')[0].reset() : '';
		$('form input, form button').attr('disabled', false);
		$('#btn-pass-recover').html(textBtn)
	});
}
