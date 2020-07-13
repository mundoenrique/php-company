'use strict'
$(function() {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false)

	$('#btn-pass-recover').on('click', function(e){
		e.preventDefault();
		var recoverBtn = $(this);
		form = $('#form-access-recovery');
		formInputTrim(form);
		validateForms(form);
		if(form.valid()) {
			var textBtn = recoverBtn.text();
			data = getDataForm(form)
			recoverBtn.html(loader);
			insertFormInput(true);
			verb = 'POST'; who = 'User'; where = 'RecoverAccess';
			callNovoCore(verb, who, where, data, function(response){
				dataResponse = response.data
				response.code == 0 ? $('form')[0].reset() : '';
				$('#btn-pass-recover').html(textBtn)
				insertFormInput(false)
			});
		}
	});
})
