'use strict'
$(function() {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false)

	$('#btn-pass-recover').on('click', function(e) {
		e.preventDefault();
		form = $('#form-access-recovery');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			btnText = $(this).text();
			data = getDataForm(form);
			$('#btn-pass-recover').html(loader);
			insertFormInput(true);
			verb = 'POST'; who = 'User'; where = 'RecoverAccess';
			callNovoCore(verb, who, where, data, function(response) {

				if (response.code == 0) {
					$('#accept').addClass('send-otp');
					var inputModal;
					inputModal = response.msg;
					inputModal +=	'<form id="otpModal" name="otpModal" onsubmit="return false" class="pt-2">';
					inputModal +=		'<div class="form-group col-auto">';
					inputModal += 		'<div class="input-group">';
					inputModal += 			'<input class="form-control" type="text" id="optCode" name="optCode" autocomplete="off">';
					inputModal += 		'</div>';
					inputModal += 		'<div class="help-block"></div>';
					inputModal += 	'</div>';
					inputModal += '</form>';

					appMessages(response.title, inputModal, response.icon, response.data)
				}


				$('#btn-pass-recover').html(btnText)
				insertFormInput(false)
			})
		}
	})

	$('#system-info').on('click', '.send-otp', function(e) {
		e.preventDefault();
		form = $('#otpModal');
		formInputTrim(form);
		validateForms(form);

		if (form.valid()) {
			$('#accept').removeClass('send-otp');
			btnText = $(this).text();
			data = getDataForm(form);
			data.email = $('#email').val();
			$('#accept')
			.html(loader)
			.prop('disabled', true)
			insertFormInput(true);
			verb = 'POST'; who = 'User'; where = 'ValidateOtp';
			callNovoCore(verb, who, where, data, function(response) {
				response.code == 0 ? $('form')[0].reset() : '';
				insertFormInput(false)
			})
		}
	});

})
