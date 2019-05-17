function validateForms(form, options) {

	var defaults = {
		debug: true,
		errorClass: "validate-error",
		validClass: "success",
		success: " ",
		ignore: ".ignore",
		errorElement: 'label'
	};
	if(options && options.errorMsg) {
		defaults.onfocusout = false;
		defaults.onkeyup = function() {};
		defaults.errorPlacement = function(error, element) {
			errorMsg = $(".general-form-msg")
			errorMsg.html('');
			if (error.html())
				errorMsg.val(options.errorMsg);
		}
	}
	jQuery.validator.setDefaults(defaults);

	var
	onlyNumber = /^[0-9]{6,8}$/,
	namesValid = /^([a-zñáéíóú.]+[\s]*)+$/i,
	validNickName = /^([a-z]{2,}[0-9_]*)$/i,
	regNumberValid = /^['a-z0-9']{6,45}$/i,
	shortPhrase = /^['a-z0-9ñáéíóú ().']{4,25}$/i,
	middlePhrase = /^['a-z0-9ñáéíóú ().']{15,45}$/i,
	longPhrase = /^['a-z0-9ñáéíóú ().']{10,70}$/i;
	emailValid = /^([a-zA-Z]+[0-9_.+-]*)+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/,
	alphanumunder = /^[\wñ]+$/,
	userPassword = /([\w!@\*\-\?¡¿+\/.,#])/,
	fiscalReg = {
		'bp': /^(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24)+(6|9)[\d]{5,6}[\d]{3,4}$/,
		'co': /^([0-9]{9,17})/,
		'pe': /^(10|15|16|17|20)[\d]{8}[\d]{1}$/,
		'us': /^(10|15|16|17|20)[\d]{8}[\d]{1}$/,
		've': /^([VEJPGvejpg]{1})-([0-9]{8})-([0-9]{1}$)/
	},
	fiscalRegMsg = {
		'bp': 'RUC',
		'co': 'NIT',
		'pe': 'RUC',
		'us': 'RUC',
		've': 'RIF'
	}

	form.validate({
		rules: {
			"user-name": {minlength: 4, pattern: alphanumunder},
			"id-company": {fiscalRegistry: true},
			"email": {pattern: emailValid},
			"new-pass": {differs: "#current-pass", validatePass: true},
			"confirm-pass": {equalTo: "#new-pass"},
			"user_login": {minlength: 4, pattern: alphanumunder},
			"user_pass": {pattern: userPassword},


			"identity-card": {pattern: onlyNumber},
			"name": {pattern: namesValid, minlength: 2},
			"last_name": {pattern: namesValid, minlength: 2},
			"nickname": {rangelength: [4, 12], pattern: validNickName},
			"specialties_checked": {specialties: true},
			"num_school_medi": {pattern: regNumberValid},
			"num_school_salud": {pattern: regNumberValid},
			"email-paypal": {pattern: emailValid},
			"client_id": {pattern: regNumberValid},
			"inv-prefix": {pattern: shortPhrase},
			"inv-content": {pattern: longPhrase},
			"noti-prefix": {pattern: longPhrase},
			"noti-content": {pattern: middlePhrase},
			"reg-prefix": {pattern: shortPhrase},
			"reg-content": {pattern: longPhrase},
			"rec-prefix": {pattern: shortPhrase},
			"rec-content": {pattern: longPhrase},
			"change-prefix": {pattern: shortPhrase},
			"change-content": {pattern: longPhrase},
			"name-speciality": {pattern: namesValid}
		},
		messages: {
			"user-name": "Debe indicar su nombre de usuario",
			"id-company": 'El '+fiscalRegMsg[country]+' no es válido',
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
			"user_login": {
				pattern: "Debe contener al menos 4 caracteres alfanuméricos",
				minlength: "Debe tener 4 caracteres como mínimo"
			},


			"identity-card": "Admite solo números min 6, max 8",
			"name": "Admite solo letras",
			"last_name": "Admite solo letras",
			"nickname": {
				required: "Permite alfabeto americano, números y \'_\' min 4, max 12",
				rangelength: "Permite alfabeto americano, números y \'_\' min 4, max 12",
				pattern: "Debe iniciar con al menos dos letras no admite \'ñ\' ni vocales acentuadas",
			},
			"tyc": "Debe aceptar los términos y condiciones",
			"specialties_checked": "Debe seleccionar al menos una especialidad",
			"num_school_medi": "Admite letras y números min 6, max 45",
			"num_school_salud": "Admite letras y números min 6, max 45",
			"email-paypal": "Indique un correo válido (xxx@xxx.xxx)",
			"client_id": "Sólo números y letras no \'ñ\' ni vocales acentuadas min 6, max 45",
			"time-session": "Mínimo 3 minutos",
			"time-token": "Mínimo 3 días",
			"time-files": "Mínimo 7 días",
			"inv-prefix": "Debe contener entre 4 y 25 caracteres",
			"inv-content": "Debe contener entre 10 y 70 caracteres",
			"noti-prefix": "Debe contener entre 10 y 70 caracteres",
			"noti-content": "Debe contener entre 15 y 45 caracteres",
			"reg-prefix": "Debe contener entre 4 y 25 caracteres",
			"reg-content": "Debe contener entre 10 y 70 caracteres",
			"rec-prefix": "Debe contener entre 4 y 25 caracteres",
			"rec-content": "Debe contener entre 10 y 70 caracteres",
			"change-prefix": "Debe contener entre 4 y 25 caracteres",
			"change-content": "Debe contener entre 10 y 70 caracteres",
			"name-speciality": "Debe indicar el nombre de la especialidad"
		}
	});

	$.validator.methods.fiscalRegistry = function(value, element, param) {
		return fiscalReg[country].test(value);
	}

	$.validator.methods.validatePass	= function(value, element, param) {
		return passStrength(value);
	}

	$.validator.methods.specialties = function(value, element) {
		return $('#select_speciality_med_agreg').children().length;
	}

	$.validator.methods.differs = function(value, element, param) {
		var target = $(param);
		return value !== target.val();
	}
}
