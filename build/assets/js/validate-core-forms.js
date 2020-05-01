'use strict'
function validateForms(form) {
	var validCountry = country;
	var onlyNumber = /^[0-9]{6,8}$/;
	var namesValid = /^([a-zñáéíóú.]+[\s]*)+$/i;
	var validNickName = /^([a-z]{2,}[0-9_]*)$/i;
	var regNumberValid = /^['a-z0-9']{6,45}$/i;
	var shortPhrase = /^['a-z0-9ñáéíóú ().']{4,25}$/i;
	var middlePhrase = /^['a-z0-9ñáéíóú ().']{5,45}$/i;
	var longPhrase = /^[a-z0-9ñáéíóú ().-]{8,70}$/i;
	var emailValid = /^([a-zA-Z]+[0-9_.+-]*)+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var alphanumunder = /^([\w.\-+&ñÑ ]+)+$/i;
	var alphanum = /^[a-z0-9]+$/i;
	var userPassword = validatePass;
	var numeric = /^[0-9]+$/;
	var alphabetical = /^[a-z]+$/i;
	var text = /^['a-z0-9ñáéíóú ,.:()']+$/i;
	var usdAmount = /^[0-9]+(\.[0-9]*)?$/;
	var fiscalReg = lang.VALIDATE_FISCAL_REGISTRY;
	var date = {
		dmy: /^(0?[1-9]|[12][0-9]|3[01])\/(0?[1-9]|1[012])\/[0-9]{4}$/,
		my: /^(0?[1-9]|1[012])\/[0-9]{4}$/,
		y: /^[0-9]{4}$/,
	};
	var defaults = {
		debug: true,
		errorClass: lang.CONF_VALID_ERROR,
		validClass: lang.CONF_VALID_VALID,
		success: lang.CONF_VALID_SUCCESS,
		ignore: lang.CONF_VALID_IGNORE,
		errorElement: lang.CONF_VALID_ELEMENT
	};

	jQuery.validator.setDefaults(defaults);

	form.validate({
		rules: {
			"user_login":	{required: true, pattern: alphanumunder},
			"user_pass": 	{verifyRequired: '#user_login', verifyPattern: '#user_login'},
			"user-name": 	{required: true, pattern: alphanumunder},
			"id-company": 	{required: true, fiscalRegistry: true},
			"email": 	{required: true, pattern: emailValid},
			"branch-office": 	{requiredBranchOffice: true},
			"type-bulk": 	{requiredTypeBulk: true},
			"file-bulk":	{required: true, extension: lang.VALIDATE_FILES_EXTENSION, sizeFile: true},
			"password": {required: true, pattern: userPassword},
			"type-order": {required: true},
			"datepicker_start": {
				required:{
					depends: function(element) {
						var requireEl = true;

						if(form.attr('id') === 'service-orders-form') {
							requireEl = !($('#five-days').is(':checked') || $('#ten-days').is(':checked'));
						}
						return requireEl;
					}
				},
				pattern: date.dmy
			},
			"datepicker_end": {
				required:{
					depends: function(element) {
						var requireEl = true;

						if(form.attr('id') === 'service-orders-form') {
							requireEl = !($('#five-days').is(':checked') || $('#ten-days').is(':checked'));
						}
						return requireEl;
					}
				},
				pattern: date.dmy
			},
			"status-order": {required: true, requiredTypeOrder: true},
			"selected-date": {required: true, pattern: date.my},
			"selected-year": {required: true, pattern: date.y},
			"id-type": {requiredSelect: true},
			"id-number": {required: true, pattern: numeric},
			"card-number": {required: true, pattern: numeric, maxlength: 16, minlength: 16},
			"card-number-sel": {requiredSelect: true},
			"inquiry-type": {requiredSelect: true}
		},
		messages: {
			"user_login": lang.VALIDATE_USERLOGIN,
			"user_pass": {
				verifyRequired: lang.VALIDATE_USERPASS_REQ,
				verifyPattern: lang.VALIDATE_USERPASS_PATT
			},
			"user-name": lang.VALIDATE_USERNAME,
			"id-company": lang.VALIDATE_ID_COMPANY,
			"email": lang.VALIDATE_EMAIL,
			"branch-office": lang.VALIDATE_BRANCH_OFFICE,
			"type-bulk": lang.VALIDATE_BULK_TYPE,
			"file-bulk": {
				required: lang.VALIDATE_FILE_TYPE,
				extension: lang.VALIDATE_FILE_TYPE,
				sizeFile: lang.VALIDATE_FILE_SIZE
			},
			"password": lang.VALIDATE_PASS,
			"type-order": lang.VALIDATE_ORDER_TYPE,
			"datepicker_start": lang.VALIDATE_INITIAL_DATE,
			"datepicker_end": lang.VALIDATE_FINAL_DATE,
			"status-order": lang.VALIDATE_ORDER_STATUS,
			"selected-date": lang.VALIDATE_SELECTED_DATE,
			"selected-year": lang.VALIDATE_SELECTED_YEAR,
			"id-type": lang.VALIDATE_ID_TYPE,
			"id-number": lang.VALIDATE_ID_NUMBER,
			"card-number": lang.VALIDATE_CARD_NUMBER,
			"card-number-sel": lang.VALIDATE_CARD_NUMBER_SEL,
			"inquiry-type": lang.VALIDATE_INQUIRY_TYPE_SEL,
		},
		errorPlacement: function(error, element) {
			$(element).closest('.form-group').find('.help-block').html(error.html());
		}
	});

	$.validator.methods.verifyRequired = function(value, element, param) {
		return value != '' && $(param).val() != '';
	}

	$.validator.methods.verifyPattern = function(value, element, param) {
		return userPassword.test(value) && alphanumunder.test($(param).val());
	}

	$.validator.methods.requiredTypeBulk = function(value, element, param) {
		var eval1 = alphanum.test($(element).find('option:selected').attr('format').trim());
		var eval2 = longPhrase.test($(element).find('option:selected').text().trim());
		var eval3 = alphanum.test($(element).find('option:selected').val().trim());
		return eval1 && eval2 && eval3;
	}

	$.validator.methods.requiredBranchOffice = function(value, element, param) {
		return alphanum.test($(element).find('option:selected').val());;
	}

	$.validator.methods.fiscalRegistry = function(value, element, param) {
		var RegExpfiscalReg = new RegExp(fiscalReg, 'i')
		return RegExpfiscalReg.test(value);
	}

	$.validator.methods.validatePass = function(value, element, param) {
		return passStrength(value);
	}

	$.validator.methods.differs = function(value, element, param) {
		var target = $(param);
		return value !== target.val();
	}

	$.validator.methods.requiredTypeOrder = function(value, element, param) {
		var eval1 = longPhrase.test($(element).find('option:selected').text().trim());
		var eval2 = alphanum.test($(element).find('option:selected').val().trim());
		return eval1 && eval2;
	}

	$.validator.methods.sizeFile = function(value, element, param) {
		return element.files[0].size > 0;
	}

	$.validator.methods.requiredSelect = function(value, element, param) {
		var valid = true;
		if($(element).find('option').length > 0 ) {
			valid = alphanum.test($(element).find('option:selected').val().trim());
		}
		return valid
	}

	form.validate().resetForm();
}
