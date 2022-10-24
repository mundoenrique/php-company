'use strict'
function validateForms(form, options) {
	var validCountry = typeof customerUri !== 'undefined' ? customerUri : isoPais;
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
	var validCode = /^[a-z0-9]+$/i;
	var fiscalReg = {
		'bp': /^(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24)+(6|9)[\d]{5,6}[\d]{3,4}$/,
		'co': /^([0-9]{9,17})/,
		'pe': /^(10|15|16|17|20)[\d]{8}[\d]{1}$/,
		'us': /^([\w_\-]+)+$/i,
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
		errorClass: "validate-error",
		validClass: "success",
		success: " ",
		ignore: ".ignore",
		errorElement: 'label'
	};

	$.validator.methods.fiscalRegistry = function(value, element, param) {
		return fiscalReg[validCountry].test(value);
	}

	$.validator.methods.validatePass	= function(value, element, param) {
		return passWordStrength(value);
	}

	$.validator.methods.differs = function(value, element, param) {
		var target = $(param);
		return value !== target.val();
	}

	if(typeof options!=='undefined') {
		if(options.handleMsg===false){
			ignoreMsgHandling();
		}
		if(options.handleStyle===false){
			errorClass = '';
		}
		if(options.modal===true){
			defaults.onfocusout=function(element) { $(element).valid(); },
			defaults.onkeyup=function(element) { $(element).valid(); },
			defaults.errorPlacement=function(error, element) {
				$(element).closest('.form-group').find('.help-block').html(error.html());
			};
		}
	} else {
		ignoreMsgHandling();
	}

	function ignoreMsgHandling() {
		defaults.onfocusout = false;
		defaults.onkeyup = function() {};
		defaults.errorPlacement = function(error, element) {
		}
	}

	jQuery.validator.setDefaults(defaults);

	form.validate({
		rules: {
			"user-name": {pattern: alphanumunder},
			"id-company": {fiscalRegistry: true},
			"email": {pattern: emailValid},
			"new-pass": {differs: "#current-pass", validatePass: true},
			"confirm-pass": {equalTo: "#new-pass"},
			"userName":{
				required: {
        	depends:function(){
            $(this).val($.trim($(this).val()));
            	return true;
        	}
				},
				pattern: alphanumunder
			},
			"userPass":{
				required: {
        	depends:function(){
            $(this).val($.trim($(this).val()));
            	return true;
        		}
				},
				pattern: userPassword
			},
			"tipo_lote_select": {pattern: numeric},
			"user-password": {pattern: userPassword},
			"user-password-1": {pattern: userPassword},
			"user-password-2": {pattern: userPassword},
			"id-persona": {pattern: numeric},
			"start-my-date": {pattern: date.my},
			"start-dmy-date": {pattern: date.dmy},
			"end-dmy-date": {pattern: date.dmy},
			"tarjeta": {pattern: numeric},
			"DNI": {pattern: numeric},
			"claveAuth": {pattern: userPassword},
			"fech_ini": {pattern: date.dmy},
			"fech_fin": {pattern: date.dmy},
			"empresa-select": {pattern: numeric},
			"producto-select": {pattern: numeric},
			"my-date": {pattern: date.my},
			"radio": {pattern: alphanum},
			"Ingrese ID": {pattern: numeric},
			"token-code": {pattern: alphanum},
			"dias": {pattern: numeric},
			"batch": {pattern: numeric},
			"ca": {pattern: alphabetical},
			"amount": {pattern: amount[validCountry]},
			"text": {pattern: text},
			"type": {pattern: alphabetical},
			"account": {pattern: numeric},
			"account-transfer": {pattern: alphanum},
			"pass": {pattern: userPassword},
			"idTipoLote": {pattern: numeric},
			"id-document": {pattern: numeric},
			"card-number": {pattern: numeric},
			"otpCode": {required: true, pattern: validCode},
			"saveIP": {pattern: numeric}
		},
		messages: {
			"user-name": "Debe indicar su nombre de usuario",
			"id-company": 'El '+fiscalRegMsg[validCountry]+' no es válido',
			"email": "Indique un correo válido (xxx@xxx.xxx)",
			"current-pass": "Indique su contraseña actual",
			"new-pass": {
				required: "Indique su nueva contraseña",
				differs: "La nueva contraseña debe ser diferente a la actual",
				validatePass: "La contraseña debe cumplir los requerimientos"
			},
			"confirm-pass": {
				required: "Confirme su nueva contraseña",
				equalTo: 'Debe ser igual a su nueva contraseña'
			},
			"start-my-date": "Falla la fecha",
			"otpCode": {
				required: 'Este campo es obligatorio.',
				pattern: 'El formato de código es inválido.',
				maxlength: 'El formato de código es inválido.'
			},
		}
	});
}
