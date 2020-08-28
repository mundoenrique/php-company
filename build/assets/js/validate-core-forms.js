'use strict'
function validateForms(form) {
	var validCountry = country;
	var onlyNumber = /^[0-9]{6,8}$/;
	var namesValid = /^([a-zñáéíóú.]+[\s]*)+$/i;
	var validNickName = /^([a-z]{2,}[0-9_]*)$/i;
	var regNumberValid = /^['a-z0-9']{6,45}$/i;
	var shortPhrase = /^['a-z0-9ñáéíóú ().']{4,25}$/i;
	var middlePhrase = /^['a-z0-9ñáéíóú ().']{5,45}$/i;
	var longPhrase = /^[a-z0-9ñáéíóú ().-]{6,70}$/i;
	var emailValid = /^([a-zA-Z]+[0-9_.+-]*)+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var alphanumunder = /^([\w.\-+&ñÑ ]+)+$/i;
	var alphanum = /^[a-z0-9]+$/i;
	var alphanumspace =  /^['a-z0-9 ']{4,25}$/i;
	var userPassword = validatePass;
	var numeric = /^[0-9]+$/;
	var alphabetical = /^[a-z]+$/i;
	var text = /^['a-z0-9ñáéíóú ,.:()']+$/i;
	var usdAmount = /^[0-9]+(\.[0-9]*)?$/;
	var validCode = /^[a-z0-9]+$/i;
	var fiscalReg = lang.VALIDATE_FISCAL_REGISTRY;
	var idNumberReg = new RegExp(lang.VALIDATE_REG_ID_NUMBER, 'i');
	var date = {
		dmy: /^(0?[1-9]|[12][0-9]|3[01])\/(0?[1-9]|1[012])\/[0-9]{4}$/,
		my: /^(0?[1-9]|1[012])\/[0-9]{4}$/,
		y: /^[0-9]{4}$/,
	};
	var binary = /^[0-1]+$/;
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
		focusInvalid: false,
		rules: {
			"user_login":	{required: true, pattern: alphanumunder},
			"user_pass": 	{verifyRequired: '#user_login', verifyPattern: '#user_login'},
			"user-name": 	{required: true, pattern: alphanumunder},
			"id-company": 	{required: true, fiscalRegistry: true},
			"email": 	{required: true, pattern: emailValid},
			"nit": 	{ pattern: numeric},
			"current-pass": {required: true},
			"new-pass": {required: true, differs: "#currentPass", validatePass: true},
			"confirm-pass": {required: true, equalTo: "#newPass"},
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

						if(form.attr('id') === 'unna-list-form') {
							requireEl = $('#bulkNumber').val() == '' && !$('#all-bulks').is(':checked');
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

						if(form.attr('id') === 'unna-list-form') {
							requireEl = $('#bulkNumber').val() == '' && !$('#all-bulks').is(':checked');
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
			"id-number1": {pattern: numeric, maxlength: 15},
			"tlf1": {required: true, pattern: numeric , maxlength: 15 },
			"card-number": {required: true, pattern: numeric, maxlength: 16, minlength: 16},
			"card-number-sel": {requiredSelect: true},
			"inquiry-type": {requiredSelect: true},
			"codeOTP": {required: true, pattern: validCode, maxlength: 8},
			"saveIP": {pattern: numeric},
			"expired-date": {required: true, pattern: date.my},
			"max-cards": {required: true, pattern: numeric, maxcards: true},
			"starting-line1": {
				required: {
					depends: function() {

						return lang.CONF_STARTING_LINE1_REQUIRED == 'ON';
					}
				},
				pattern: alphanumspace
			},
			"starting-line2": {
				required: {
					depends: function() {

						return lang.CONF_STARTING_LINE2_REQUIRED == 'ON';
					}
				},
				pattern: alphanumspace
			},
			"bulk-number": {pattern: numeric},
			"enterpriseName": {required: true},
			"productName": {required: true},
			"enterpriseCode": {requiredSelect: true},
			"productCode": {requiredSelect: true},
			"checkbox": {pattern: binary},
			"initialDate": {required: true, pattern: date.dmy},
			"finalDate": {required: true, pattern: date.dmy},
			"initialDatemy": {required: true, pattern: date.my},
			"finalDatemy": {required: true, pattern: date.my},
      "monthYear": {required: true, pattern: date.my},
			"idNumber": {pattern: idNumberReg},
			"anio-consolid": { requiredSelect: true, min: 1, pattern: date.y},
			"cardNumber": {
				required: {
					depends: function (element) {
						var validate = false;
						if ($(element).attr('req') == 'yes') {
							var validate = true;
						}

						return validate
					}
				},
				pattern: numeric, maxlength: 16, minlength: 16
			},
			"otpCode": {required: true, pattern: alphanum},
			"orderNumber": {pattern: numeric, require_from_group: [1, '.select-group']},
			"bulkNumber": {pattern: numeric, require_from_group: [1, '.select-group']},
			"idNumberP": {
				required: {
					depends: function (element) {
						var valid = false;

						if (lang.CONF_INQUIRY_DOCTYPE == 'ON') {
							valid = alphabetical.test($('#docType').val()) && $('#docType').val() != '';
						}

						return valid;
					}
				},
				pattern: idNumberReg, require_from_group: [1, '.select-group']
			},
			"docType": {
				required: {
					depends: function (element) {
						return idNumberReg.test($('#idNumberP').val())
					}
				},
				pattern: alphabetical
			},
			"cardNumberP": {pattern: numeric, minlength: lang.VALIDATE_MINLENGTH, require_from_group: [1, '.select-group']},
			"masiveOptions": {requiredSelect: true},
			"documentId": {required: true, pattern: alphanum},
			"documentType": {requiredSelect: true},
			"optCode": {required: true, pattern: alphanum},
			"numberDayPurchasesCtp": {required: true, pattern: numeric, maxLimitZero:'#numberWeeklyPurchasesCtp', minLimitZero:'#numberWeeklyPurchasesCtp'},
			"numberWeeklyPurchasesCtp": {required: true, pattern: numeric, maxLimitZero: '#numberMonthlyPurchasesCtp', minLimitZero: '#numberMonthlyPurchasesCtp'},
			"dailyPurchaseamountCtp": {required: true, pattern: numeric, maxLimitZero:'#weeklyAmountPurchasesCtp', minLimitZero:'#weeklyAmountPurchasesCtp'},
			"weeklyAmountPurchasesCtp": {required: true, pattern: numeric, maxLimitZero:'#monthlyPurchasesAmountCtp', minLimitZero:'#monthlyPurchasesAmountCtp'},
			"numberDayPurchasesStp": {required: true, pattern: numeric, maxLimitZero:'#numberWeeklyPurchasesStp', minLimitZero:'#numberWeeklyPurchasesStp'},
			"numberWeeklyPurchasesStp": {required: true, pattern: numeric, maxLimitZero:'#numberMonthlyPurchasesStp', minLimitZero:'#numberMonthlyPurchasesStp'},
			"dailyPurchaseamountStp": {required: true, pattern: numeric, maxLimitZero:'#weeklyAmountPurchasesStp', minLimitZero:'#weeklyAmountPurchasesStp'},
			"weeklyAmountPurchasesStp": {required: true, pattern: numeric, maxLimitZero:'#monthlyPurchasesAmountStp', minLimitZero:'#monthlyPurchasesAmountStp'},
			"dailyNumberWithdraw": {required: true, pattern: numeric, maxLimitZero:'#weeklyNumberWithdraw', minLimitZero:'#weeklyNumberWithdraw'},
			"weeklyNumberWithdraw": {required: true, pattern: numeric, maxLimitZero:'#monthlyNumberWithdraw', minLimitZero:'#monthlyNumberWithdraw'},
			"dailyAmountWithdraw": {required: true, pattern: numeric, maxLimitZero:'#weeklyAmountWithdraw', minLimitZero:'#weeklyAmountWithdraw'},
			"weeklyAmountWithdraw": {required: true, pattern: numeric, maxLimitZero:'#monthlyAmountwithdraw', minLimitZero:'#monthlyAmountwithdraw'},
			"dailyNumberCredit": {required: true, pattern: numeric, maxLimitZero:'#weeklyNumberCredit', minLimitZero:'#weeklyNumberCredit'},
			"weeklyNumberCredit": {required: true, pattern: numeric, maxLimitZero:'#monthlyNumberCredit', minLimitZero:'#monthlyNumberCredit'},
			"dailyAmountCredit": {required: true, pattern: numeric, maxLimitZero:'#weeklyAmountCredit', minLimitZero:'#weeklyAmountCredit'},
			"weeklyAmountCredit": {required: true, pattern: numeric, maxLimitZero:'#monthlyAmountCredit', minLimitZero:'#monthlyAmountCredit'},
			"numberMonthlyPurchasesCtp": {pattern: numeric , required: true },
			"monthlyPurchasesAmountCtp": {pattern: numeric , required: true },
			"purchaseTransactionCtp": {pattern: numeric , required: true,minLimitZero:'#dailyPurchaseamountCtp', maxLimitZero: '#dailyPurchaseamountCtp'},
			"numberMonthlyPurchasesStp": {pattern: numeric , required: true },
			"monthlyPurchasesAmountStp": {pattern: numeric , required: true },
			"purchaseTransactionStp": {pattern: numeric , required: true, maxLimitZero:'#dailyPurchaseamountStp', minLimitZero:'#dailyPurchaseamountStp'},
			"monthlyNumberWithdraw": {pattern: numeric , required: true },
			"monthlyAmountwithdraw": {pattern: numeric , required: true },
			"WithdrawTransaction": {pattern: numeric , required: true, maxLimitZero:'#dailyAmountWithdraw', minLimitZero:'#dailyAmountWithdraw'},
			"monthlyNumberCredit": {pattern: numeric, required: true },
			"monthlyAmountCredit": {pattern: numeric , required: true },
			"CreditTransaction": {pattern: numeric , required: true, maxLimitZero:'#dailyAmountCredit', minLimitZero:'#dailyAmountCredit'},
		},
		messages: {
			"user_login": lang.VALIDATE_USERLOGIN,
			"user_pass": {
				verifyRequired: lang.VALIDATE_USERPASS_REQ,
				verifyPattern: lang.VALIDATE_USERPASS_PATT
			},
			"user-name": lang.VALIDATE_USERNAME,
			"nit": lang.VALIDATE_USERNAME,
			"id-company": lang.VALIDATE_ID_COMPANY,
			"anio-consolid": lang.VALIDATE_SELECTED_YEAR,
			"email": lang.VALIDATE_EMAIL,
			"current-pass": lang.VALIDATE_CURRENT_PASS,
			"new-pass": {
				required: lang.VALIDATE_NEW_PASS,
				differs: lang.VALIDATE_DIFFERS_PASS,
				validatePass: lang.VALIDATE_REQUIREMENTS_PASS
			},
			"confirm-pass": {
				required: lang.VALIDATE_CONFIRM_PASS,
				equalTo: 'Debe ser igual a la nueva contraseña'
			},
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
			"id-number1": {
				pattern: lang.VALIDATE_ID_NUMBER,
				maxlength: lang.VALIDATE_LENGHT_NUMBER,
			},
			"tlf1": {
				pattern: lang.VALIDATE_ID_NUMBER,
				required: lang.VALIDATE_PHONE_REQ,
				maxlength: lang.VALIDATE_LENGHT_NUMBER
			},
			"card-number": lang.VALIDATE_CARD_NUMBER,
			"card-number-sel": lang.VALIDATE_CARD_NUMBER_SEL,
			"inquiry-type": lang.VALIDATE_INQUIRY_TYPE_SEL,
			"codeOTP": {
				required: lang.GEN_CODE_OTP_REQUIRED,
				pattern: lang.GEN_CODE_OTP_INVALID_FORMAT,
				maxlength: lang.GEN_CODE_OTP_INVALID_FORMAT
			},
			"expired-date": lang.VALIDATE_SELECTED_DATE,
			"max-cards": lang.VALIDATE_TOTAL_CARDS,
			"starting-line1": lang.VALIDATE_STARTING_LINE,
			"starting-line2": lang.VALIDATE_STARTING_LINE,
			"bulk-number": lang.VALIDATE_BULK_NUMBER,
			"enterpriseCode": lang.VALIDATE_SELECT_ENTERPRISE,
			"productCode": lang.VALIDATE_SELECT_PRODUCT,
			"initialDate": lang.VALIDATE_DATE_DMY,
			"finalDate": lang.VALIDATE_DATE_DMY,
			"initialDatemy": lang.VALIDATE_DATE_MY,
			"monthYear": lang.VALIDATE_DATE_MY,
			"idNumber": lang.VALIDATE_ID_NUMBER,
			"cardNumber": lang.VALIDATE_CARD_NUMBER,
			"otpCode": lang.VALIDATE_OS_OTP,
			"orderNumber": {
				pattern: lang.VALIDATE_BULK_NUMBER,
				require_from_group: lang.VALIDATE_SELECT_GROUP

			},
			"bulkNumber": {
				pattern: lang.VALIDATE_BULK_NUMBER,
				require_from_group: lang.VALIDATE_SELECT_GROUP

			},
			"idNumberP": {
				required: lang.VALIDATE_ID_NUMBER,
				pattern: lang.VALIDATE_ID_NUMBER,
				require_from_group: lang.VALIDATE_SELECT_GROUP
			},
			"docType": {
				required: lang.VALIDATE_SELECT_DOCTYPE,
				pattern: lang.VALIDATE_SELECT_DOCTYPE
			},
			"cardNumberP": {
				pattern: lang.VALIDATE_CARD_NUMBER_MIN,
				minlength: lang.VALIDATE_CARD_NUMBER_MIN,
				require_from_group: lang.VALIDATE_SELECT_GROUP
			},
			"masiveOptions": lang.VALIDATE_OPTION,
			"documentId": lang.VALIDATE_DOCUMENT_ID,
			"documentType": lang.VALIDATE_SELECT_DOCTYPE,
			"optCode": lang.VALIDATE_OTP_CODE,
			"numberMonthlyPurchasesCtp": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"monthlyPurchasesAmountCtp": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"purchaseTransactionCtp": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ , minLimitZero: lang.VALIDATE_MAX_DAY},
			"numberMonthlyPurchasesStp": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"monthlyPurchasesAmountStp": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"purchaseTransactionStp": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ, maxLimitZero: lang.VALIDATE_MAX_DAY},
			"monthlyNumberWithdraw": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"monthlyAmountwithdraw": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"WithdrawTransaction": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ, maxLimitZero: lang.VALIDATE_MAX_DAY, },
			"monthlyNumberCredit": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"monthlyAmountCredit": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"CreditTransaction": {pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ, maxLimitZero: lang.VALIDATE_MAX_DAY},
			"numberDayPurchasesCtp": {maxLimitZero: lang.VALIDATE_MAX_WEEK, pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"numberWeeklyPurchasesCtp": {maxLimitZero: lang.VALIDATE_MAX_MONTH, pattern: lang.VALIDATE_INVALID_NUMBER, required:lang.VALIDATE_NUMBER_REQ},
			"dailyPurchaseamountCtp": {maxLimitZero: lang.VALIDATE_MAX_WEEK, pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"weeklyAmountPurchasesCtp": {maxLimitZero: lang.VALIDATE_MAX_MONTH, pattern: lang.VALIDATE_INVALID_NUMBER, required:lang.VALIDATE_NUMBER_REQ},
			"numberDayPurchasesStp": {maxLimitZero: lang.VALIDATE_MAX_WEEK, pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"numberWeeklyPurchasesStp": {maxLimitZero: lang.VALIDATE_MAX_MONTH, pattern: lang.VALIDATE_INVALID_NUMBER, required:lang.VALIDATE_NUMBER_REQ},
			"dailyPurchaseamountStp": {maxLimitZero: lang.VALIDATE_MAX_WEEK, pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"weeklyAmountPurchasesStp": {maxLimitZero: lang.VALIDATE_MAX_MONTH, pattern: lang.VALIDATE_INVALID_NUMBER, required:lang.VALIDATE_NUMBER_REQ},
			"dailyNumberWithdraw": {maxLimitZero: lang.VALIDATE_MAX_WEEK, pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"weeklyNumberWithdraw": {maxLimitZero: lang.VALIDATE_MAX_MONTH, pattern: lang.VALIDATE_INVALID_NUMBER, required:lang.VALIDATE_NUMBER_REQ},
			"dailyAmountWithdraw": {maxLimitZero: lang.VALIDATE_MAX_WEEK, pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"weeklyAmountWithdraw": {maxLimitZero: lang.VALIDATE_MAX_MONTH, pattern: lang.VALIDATE_INVALID_NUMBER, required:lang.VALIDATE_NUMBER_REQ},
			"dailyNumberCredit": {maxLimitZero: lang.VALIDATE_MAX_WEEK, pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"weeklyNumberCredit": {maxLimitZero: lang.VALIDATE_MAX_MONTH, pattern: lang.VALIDATE_INVALID_NUMBER, required:lang.VALIDATE_NUMBER_REQ},
			"dailyAmountCredit": {maxLimitZero: lang.VALIDATE_MAX_WEEK, pattern: lang.VALIDATE_INVALID_NUMBER , required: lang.VALIDATE_NUMBER_REQ },
			"weeklyAmountCredit": {maxLimitZero: lang.VALIDATE_MAX_MONTH, pattern: lang.VALIDATE_INVALID_NUMBER, required:lang.VALIDATE_NUMBER_REQ},
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
		return alphanum.test($(element).find('option:selected').val());
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
		var eval1 = alphanumunder.test($(element).find('option:selected').text().trim());
		var eval2 = alphanum.test($(element).find('option:selected').val().trim());
		return eval1 && eval2;
	}

	$.validator.methods.sizeFile = function(value, element, param) {
		return element.files[0].size > 0;
	}

	$.validator.methods.requiredSelect = function(value, element, param) {
		var valid = true;

		if($(element).find('option').length > 0 ) {
			valid = alphanumunder.test($(element).find('option:selected').val().trim());
		}

		return valid
	}

	$.validator.methods.maxcards = function(value, element, param) {
		var valid = true;
		var cardsMax = parseInt($(element).attr('max-cards'));
		var cards = parseInt(value);

		valid = cards > 0;

		if (cardsMax > 0 && valid) {
			valid = cardsMax > cards
		}

		return valid
	}

	$.validator.methods.minLimitZero = function(value, element, param) {
		var valid = true;
		if (value == 0) {
			if (Number($(param).val() == 0 )) {
				valid = true;
			}else{
				valid = false;
			}
		}
		return valid;
	}

	$.validator.methods.maxLimitZero = function(value, element, param) {
		var valid;
		if (Number($(param).val()) == 0 ) {
			if (value >= Number($(param).val()) ) {
				valid = true
			} else {
				valid = false
			}
		}else if (Number($(param).val()) > 0 ) {
			if (value <= Number($(param).val()) && value > 0) {
				valid = true
			} else {
				valid = false
			}
		}
		return valid;
	}

	form.validate().resetForm();
}
