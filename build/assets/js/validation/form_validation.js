'use strict';
const formValidation = function (form) {
	clearInputForm(form);

	const formId = form.prop('id');
	const regExpUserName = new RegExp(lang.REGEX_USER_NAME, 'i');
	const regExpPassword = new RegExp(lang.REGEX_PASSWORD, 'i');

	jQuery.validator.setDefaults({
		debug: true,
		errorClass: lang.SETT_VALID_ERROR,
		validClass: lang.SETT_VALID_VALID,
		success: lang.SETT_VALID_SUCCESS,
		ignore: lang.SETT_VALID_IGNORE,
		errorElement: lang.SETT_VALID_ELEMENT,
	});
	// require_from_group: [2, '.required-group']
	form.validate({
		rules: {
			userName: { required: true, pattern: regExpUserName },
			userPass: {
				required: {
					depends: function () {
						return formId !== 'signInForm';
					},
				},
				userPassReq: '#userName',
				userPassPattern: '#userName',
				pattern: regExpPassword,
			},
		},
		messages: {
			userName: {
				pattern: lang.VALID_USERNAME,
			},
			userPass: {
				userPassReq: lang.VALID_USERPASS_REQ,
				userPassPattern: lang.VALID_USERPASS_PATTERN,
				pattern: lang.VALID_PASSWORD,
			},
		},
		errorPlacement: function (error, element) {
			$(element).closest('.form-group').find('.help-block').html(error.html());
		},
	});

	$.validator.methods.userPassReq = function (value, element, param) {
		let valid = true;

		if (formId === 'signInForm') {
			valid = value !== '' && $(param).val() !== '';
		}

		return valid;
	};

	$.validator.methods.userPassPattern = function (value, element, param) {
		let valid = true;

		if (formId === 'signInForm') {
			valid = regExpPassword.test(value) && regExpUserName.test($(param).val());
		}

		return valid;
	};
};

const clearInputForm = function (form) {
	form.find('input:not([type=file]), select, textarea').each(function () {
		let thisValInput = $(this).val();

		if (thisValInput === null) {
			return;
		}

		let trimVal = thisValInput.trim();

		if ($(this).prop('tagName') === 'SELECT') {
			$(this).find('option:selected').val(trimVal);
		} else {
			$(this).val(trimVal);
		}
	});
};
