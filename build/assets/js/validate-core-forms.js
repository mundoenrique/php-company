'use strict'
function validateForms(form) {
	var validCountry = typeof country!=='undefined'? country : isoPais;
	var onlyNumber = /^[0-9]{6,8}$/;
	var namesValid = /^([a-zñáéíóú.]+[\s]*)+$/i;
	var validNickName = /^([a-z]{2,}[0-9_]*)$/i;
	var regNumberValid = /^['a-z0-9']{6,45}$/i;
	var shortPhrase = /^['a-z0-9ñáéíóú ().']{4,25}$/i;
	var middlePhrase = /^['a-z0-9ñáéíóú ().']{15,45}$/i;
	var longPhrase = /^['a-z0-9ñáéíóú ().']{10,70}$/i;
	var emailValid = /^([a-zA-Z]+[0-9_.+-]*)+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var alphanumunder = /^([\w.\-+&ñÑ]+)+$/i;
	var alphanum = /^[a-z0-9]+$/i;
	var userPassword = /^[\w!@\*\-\?¡¿+\/.,#]+$/;
	var numeric = /^[0-9]+$/;
	var alphabetical = /^[a-z]+$/i;
	var text = /^['a-z0-9ñáéíóú ,.:()']+$/i;
	var usdAmount = /^[0-9]+(\.[0-9]*)?$/;
	var fiscalReg = {
		'bp': /^(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24)+(6|9)[\d]{5,6}[\d]{3,4}$/,
		'co': /^([0-9]{9,17})/,
		'pe': /^(10|15|16|17|20)[\d]{8}[\d]{1}$/,
		'us': /^(10|15|16|17|20)[\d]{8}[\d]{1}$/,
		've': /^([VEJPGvejpg]{1})-([0-9]{8})-([0-9]{1}$)/
	};
	var date = {
		dmy: /^(0?[1-9]|[12][0-9]|3[01])\/(0?[1-9]|1[012])\/[0-9]{4}$/,
		my: /^(0?[1-9]|1[012])\/[0-9]{4}$/,
	};
	var amount = {
		'Ec-bp': usdAmount,
		'bp': usdAmount
	};
	var fiscalRegMsg = {
		'bp': 'RUC',
		'co': 'NIT',
		'pe': 'RUC',
		'us': 'RUC',
		've': 'RIF'
	};
	var defaults = {
		debug: true,
		errorClass: lang.VALIDATE_ERROR,
		validClass: lang.VALIDATE_VALID,
		success: lang.VALIDATE_SUCCESS,
		ignore: lang.VALIDATE_IGNORE,
		errorElement: lang.VALIDATE_ELEMENT
	};

	jQuery.validator.setDefaults(defaults);

	form.validate({
		rules: {
			"user_login":{required: true, pattern: alphanumunder},
			"user_pass":{verifyRequired: '#user_login', verifyPattern: '#user_login'}
		},
		messages: {
			"user_login": lang.VALIDATE_USERLOGIN,
			"user_pass": {
				verifyRequired: lang.VALIDATE_USERPASS_REQ,
				verifyPattern: lang.VALIDATE_USERPASS_PATT
			},
		},
		errorPlacement: function(error, element) {
			$(element).closest('.form-group').find('.help-block').html(error.html());
		}
	});

	$.validator.methods.fiscalRegistry = function(value, element, param) {
		return fiscalReg[validCountry].test(value);
	}

	$.validator.methods.validatePass	= function(value, element, param) {
		return passStrength(value);
	}

	$.validator.methods.differs = function(value, element, param) {
		var target = $(param);
		return value !== target.val();
	}

	$.validator.methods.verifyRequired = function(value, element, param) {
		return value != '' && $(param).val() != '';
	}

	$.validator.methods.verifyPattern = function(value, element, param) {
		return userPassword.test(value) && alphanumunder.test($(param).val());
	}
}
